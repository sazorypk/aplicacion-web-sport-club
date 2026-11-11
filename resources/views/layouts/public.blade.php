<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title', 'Sport Club')</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"><link rel="stylesheet" href="{{ asset('css/public-header.css') }}"></head>
<body>
@guest
@include('layouts.public-header')
@else
<nav class="navbar navbar-expand-lg navbar-dark bg-dark account-nav">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ auth()->user()->role === 'master' ? route('admin.dashboard') : route('dashboard') }}">
            <img src="{{ asset('assets/img/logo.png') }}" alt="" class="bg-white rounded-2 p-1 object-fit-contain" width="40" height="40">
            <span>Sport Club</span>
        </a>
        <div class="d-flex gap-2 align-items-center ms-auto">
            <a class="btn btn-outline-light btn-sm" href="{{ route('profile.edit') }}">Mi perfil</a>
            @if(auth()->user()->role === 'master')
                <a class="btn btn-outline-light btn-sm" href="{{ route('admin.dashboard') }}">Panel maestro</a>
            @else
                <a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Mi panel</a>
            @endif
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-light btn-sm">Cerrar sesión</button></form>
        </div>
    </div>
</nav>
@endguest
<main>@yield('content')</main>
<footer class="border-top mt-5 py-4"><div class="container text-secondary small">Sport Club · <a href="{{ route('privacy.policy') }}">Política de privacidad</a></div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
