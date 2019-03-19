<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
@include('email.header')
    <div style='border: ridge; padding: 10px;'>
        {!! $content !!}
    </div>
@include('email.footer')
</body>
</html>
