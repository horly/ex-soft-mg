<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $subject }}</title>
</head>
<body>
    <div style="margin-bottom: 20px">
        {{ __('auth.hi') }} <b>{{ $name }}</b>
    </div>

    <div style="margin-bottom: 20px">
        <p><b>{{ $nameExp }}</b> {{ __('auth.has_invited_you_to_join') }}</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.your_credentials_is') }}</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>
            {{ __('auth.email') }} : <b>{{ $email }}</b><br>
            {{ __('auth.password') }} : <b>{{ $password }}</b>
        </p>
    </div>

    <div style="margin-bottom: 20px">
        <p>
            {{ __('auth.you_can_login') }} <a target="__blank" href="{{ route('login') }}">{{ __('auth.here') }}</a>
            {{ __('auth.or_you_can_create') }} <a target="__blank" href="{{ route('app_reset_password', ['secret' => $verification_code_secret]) }}">{{ __('auth.here') }}</a>.
        </p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.thanks') }}</p>
    </div>

    @include('mail.team')

</body>
</html>
