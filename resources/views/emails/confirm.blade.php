{{-- Hola {{ $user->name }}
Has cambiado tu correo electrónico.. Por favor verifica la nueva dirección usando el siguiente enlace:

{{ route('verify', $user->verification_token) }} --}}

<x-mail::message>
# Hola {{ $user->name }}

Has cambiado tu correo electrónico.. Por favor verifica la nueva dirección usando el siguiente botón:

<x-mail::button :url="route('verify', $user->verification_token)">
Confirmar mi Cuenta
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>

