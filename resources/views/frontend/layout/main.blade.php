<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Clepuis</title>
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    @include('frontend.layout.styles')
    @stack('styles')

    @yield('extra_meta')

</head>
<body>
@include('frontend.shared.preloader')

@include('frontend.layout.header')

@yield('content')


{{--login form--}}
@include('frontend.shared.login')
@include('frontend.shared.register')


@include('frontend.layout.footer')

@include('frontend.layout.scripts')

@stack('scripts')
</body>
</html>
