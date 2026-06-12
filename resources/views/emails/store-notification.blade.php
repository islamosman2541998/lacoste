<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <title>{{ $mailSubject }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.8;">
    <h2>{{ $mailSubject }}</h2>

    <div>
        {!! nl2br(e($mailBody)) !!}
    </div>
</body>
</html>