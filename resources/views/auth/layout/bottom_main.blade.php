<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<style>

</style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/x-icon" href="{{asset('images/favicon.png')}}">
    @yield('extra_meta')
    @include('frontend.layout.styles')
    @stack('styles')

</head>
<body>

@include('frontend.layout.header')

<div class="content-wrapper">
    @yield('content')
</div>

@include('frontend.layout.scripts')

@stack('scripts')
</body>
</html>
