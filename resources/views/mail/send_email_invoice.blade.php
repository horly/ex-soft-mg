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
        {{ $greeting }} <b>{{ $recipient_name }}</b>
    </div>

    <div style="margin-bottom: 20px">
        <p>{{ $message_email }}</p>
    </div>

    <a href="{{ $url }}" target="__blank">{{ __('invoice.download_the_invoice_here') }}</a>

    <div style="margin-bottom: 20px">
        <p>
            {{ __('invoice.invoice_sent_by') }} <br>
            <a href="{{ route('login') }}" target="__blank"><b>{{ config('app.name') }}</b></a>
        </p>
    </div>

</body>
</html>
