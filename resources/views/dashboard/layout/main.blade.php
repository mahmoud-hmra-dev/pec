<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="{{asset('favicon.ico')}}">
    <title>{{env('APP_NAME', 'PEC')}} - Dashboard</title>
    @include('dashboard.layout.styles')
    <style>
        table.dataTable tr td:last-child {
            white-space: unset;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

    @include('dashboard.layout.navbar')

    @include('dashboard.layout.sidebar')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>
    <!-- /.content-wrapper -->

@include('dashboard.layout.sidebar')

</div>

@include('dashboard.layout.scripts')

@stack('scripts')
</body>
</html>
