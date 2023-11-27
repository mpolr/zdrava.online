<x-mail::message>
# {{ __('Email verification') }}

{{ __('Hello! Thank you for signing up.') }}
{{ __('To complete registration, confirm your email address by clicking the button below.') }}

<x-mail::button :url="route('auth.verify.email', $pin)">
    {{ __('Verify email') }}
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
