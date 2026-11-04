<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sport Club: disciplinas, planes y espacios para entrenar en comunidad.">
    <title>@yield('title', 'Sport Club')</title>
    <link rel="icon" href="{{ asset('assets/img/logo.ico') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/public-pages.css') }}">
    @stack('head')
</head>
<body class="marketing-page">
    @include('layouts.public-header')
    <main>@yield('content')</main>
    @include('layouts.public-footer')
    @stack('scripts')
</body>
</html>
