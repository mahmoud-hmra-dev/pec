<!DOCTYPE html>
<html>
<head>
    <title>{{env('APP_NAME', 'laravel')}}</title>
</head>
<body>

<p> Dear ,</p>

<p>Click on the link to change your password</p>

<a href="{{route('password.reset' , $code)}}">reset password</a>

<p>For any Question you can contact us at <a href="mailto:{{env("MAIL_FROM_ADDRESS")}}">{{env("MAIL_FROM_ADDRESS")}}</a></p>

<p>Best Regards,</p>
<p>{{env('APP_NAME', 'laravel')}} Team.</p>
</body>
</html>
