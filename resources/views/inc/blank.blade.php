<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/vendors.bundle.css') }}">
    <link rel="stylesheet" media="screen, print" href="{{ asset('css/app.bundle.css') }}">
    @yield('extended-css')
    <style>
        html,
        body {
            height: 100%;
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            overflow: hidden !important;
        }
    </style>
</head>

<body>
    @yield('content')
    <script src="{{ asset('js/vendors.bundle.js') }}"></script>
    <script src="{{ asset('js/app.bundle.js') }}"></script>
    @vite('resources/js/app.js')
    @yield('plugin')
</body>

</html>
