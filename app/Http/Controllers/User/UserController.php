<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;

// class UserController extends Controller
class UserController extends ApiController
{
    public function __construct()
    {
        // parent::__construct();
        //
        $this->middleware('client.credentials')->only(['store', 'resend']);
        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        $this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
        $this->middleware('scope:manage-account')->only(['show', 'update']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = User::all();
        // $usuarios = User::get();

        // return $usuarios;
        // return response()->json($usuarios, 200);
        // return response()->json(['data' => $usuarios], 200);
        return $this->showAll($usuarios);

        // $headers = [
        //     'Content-Type' => 'application/json; charset=utf-8',
        // ];
        // return response()->json($usuarios, 200, $headers);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // $reglas = [
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6|confirmed',
        // ];

        $reglas = [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
        ];

        // $this->validate($request, $reglas); // En Laravel 10<
        // request()->validate($reglas);
        $request->validate($reglas);

        $campos = $request->all();
        // dd($campos);
        // $campos['password'] = bcrypt($request->password);
        $campos['password'] = Hash::make($request->password);
        $campos['email_verified_at'] = null; // Fecha y hora en que el email fue verificado.
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verification_token'] = User::generarVerificationToken(); // Código de verificación de electrónico
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);

        // // 2da Forma
        // $usuario = User::create([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => bcrypt($request->password),
        //     'password_confirmation' => $request->password,
        //     'verified' => User::USUARIO_NO_VERIFICADO,
        //     'verification_token' => User::generarVerificationToken(),
        //     'admin' => User::USUARIO_REGULAR,
        // ]);

        // return response()->json(['data' => $usuario], 201);
        return $this->showOne($usuario, 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    public function show(User $user)
    {
        // $usuario = User::find($id);
        // $usuario = User::findOrFail($id);

        // return response()->json(['data' => $usuario], 200);
        // return $this->showOne($usuario);
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    public function update(Request $request, User $user)
    {
        // $user = User::findOrFail($id);
        // dd($user);

        // dd($user, request()->all(), $request->all());
        //
        // $reglas = [
        //     // 'email' => 'email|unique:users,email,' . $user->id,
        //     'email' => ['email', Rule::unique('users')->ignore($user->id)],
        //     'password' => 'min:6|confirmed',
        //     'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        // ];

        $reglas = [
            'email' => ['email', 'unique:users,email,' . $user->id],
            // 'email' => ['email', Rule::unique('users')->ignore($user->id)],
            // 'email' => ['email', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['min:6', 'confirmed'],
            'admin' => ['in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR],
        ];

        // request()->validate($reglas);
        $request->validate($reglas);

        // dd(request()->all(), $request->all(), $user, $request->validate($reglas));
        //
        if ($request->has('name')) { // Si la petición tiene un campo name
            $user->name = $request->name;
            // $user->name = request()->name;
            // $user->name = request()->input('name');
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->email_verified_at = null; // Fecha y hora en que el email fue verificado.
            $user->verified = User::USUARIO_NO_VERIFICADO; // Obs: Si es 1 y se cambia a 0 entonces en $user->esVerificado() devuelve Falso
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
            // $user->password = Hash::make($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->esVerificado()) {
                // return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador.', 'code' => 409], 409);

                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador.', 409);
            }
            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) { // isDirty determina si alguno de los atributos del modelo ha cambiado respecto al valor actual
            // return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar.', 'code' => 422], 422);
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar.', 422);
        }

        $user->save();

        // return response()->json(['data' => $user], 200);
        return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    public function destroy(User $user)
    {
        // $user = User::findOrFail($id);

        $user->delete();

        // return response()->json(['data' => $user], 200);
        return $this->showOne($user);
    }

    public function verify(string $token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;
        $user->email_verified_at = now();
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('La cuenta ha sido verificada.');
    }

    public function resend(User $user)
    {
        if ($user->esVerificado()) {
            return $this->errorResponse('Este usuario ya ha sido verificado.', 409);
        }

        retry(5, function () use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('El correo de verificación se ha reenviado.');
    }
}
