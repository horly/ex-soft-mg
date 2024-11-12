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
        <p>{{ __('auth.a_sin_in_attempt_requires') }}</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.device') }} : <b>{{ $browser }} {{ __('auth.on') }} {{ $platform }}</b><br>
            {{ __('auth.verification_code') }} : <b>{{ $verification_code }}</b><br>
            {{ __('auth.time_and_date') }} : <b>{{ $time_date }}</b></p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.if_you_did_not_attempt') }}. {{ __('auth.click') }} <a target="__blank" href="{{ route('app_reset_password', ['secret' => $verification_code_secret]) }}">{{ __('auth.here') }}</a> {{ __('auth.to_create_a_new_strong') }}.</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.thanks') }}</p>
    </div>

    @include('mail.team')

</body>
</html>
