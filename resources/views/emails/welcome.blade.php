{{-- Hola {{ $user->name }}
Gracias por crear una cuenta. Por favor verifícala usando el siguiente enlace:

{{ route('verify', $user->verification_token) }}
 --}}

 <x-mail::message>
# Hola {{ $user->name }}

Gracias por crear una cuenta. Por favor verifícala usando el siguiente botón:

<x-mail::button :url="route('verify', $user->verification_token)">
Confirmar mi Cuenta
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>

