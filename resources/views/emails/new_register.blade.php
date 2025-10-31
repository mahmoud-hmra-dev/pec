<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME', 'laravel')}}</title>
</head>
<body>

<p> Dear {{$user->first_name . " " .$user->last_name}} ,</p>

<p>Glad having you on our platform.</p>

<p>Please for further inquiries, don't hesitate to contact us at: <a href="mailto:{{env("MAIL_FROM_ADDRESS")}}">{{env("MAIL_FROM_ADDRESS")}}</a></p>
<p>{{env('APP_NAME', 'laravel')}} Team.</p>
</body>
</html>
