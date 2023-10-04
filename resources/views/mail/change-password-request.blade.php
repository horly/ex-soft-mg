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
        <p>{{ __('profile.weve_received_a_request_to_reset_your_password') }}</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.device') }} : <b>{{ $browser }} {{ __('auth.on') }} {{ $platform }}</b><br>
            {{ __('auth.time_and_date') }} : <b>{{ $time_date }}</b></p>
    </div>
    
    <a href="{{ route('app_reset_password', ['secret' => $verification_code_secret]) }}" target="__blank">{{ __('auth.reset_your_password') }}</a>
    
    <div style="margin-bottom: 20px">
        <p>{{ __('profile.if_you_did_not_request_a_new_password') }}</p>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ __('auth.thanks') }}</p>
    </div>
    
    <div style="margin-bottom: 20px">
        <b>{{ __('auth.the_exad_team') }}.</b>
    </div>

</body>
</html>