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
        <p>{{ __('profile.change_email_address_message_request_we_receive') }}</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.device') }} : <b>{{ $browser }} {{ __('auth.on') }} {{ $platform }}</b><br>
            {{ __('auth.time_and_date') }} : <b>{{ $time_date }}</b></p>
    </div>

    <a href="{{ route('app_change_email_address', ['token' => $verification_code_secret]) }}" target="__blank">{{ __('auth.change_email_address') }}</a>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.thanks') }}</p>
    </div>

    @include('mail.team')

</body>
</html>
