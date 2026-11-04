<!doctype html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>@yield('title', 'Sport Club')</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"></head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark"><div class="container"><a class="navbar-brand" href="{{ route('inicio') }}">Sport Club</a><button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button><div class="collapse navbar-collapse" id="mainNav">@guest<ul class="navbar-nav me-auto"><li class="nav-item"><a class="nav-link" href="{{ route('inicio') }}">Inicio</a></li><li class="nav-item"><a class="nav-link" href="{{ route('nosotros') }}">Nosotros</a></li><li class="nav-item"><a class="nav-link" href="{{ route('disciplinas') }}">Disciplinas</a></li><li class="nav-item"><a class="nav-link" href="{{ route('planes') }}">Planes</a></li><li class="nav-item"><a class="nav-link" href="{{ route('contacto') }}">Contacto</a></li></ul>@endguest<div class="d-flex gap-2 align-items-center ms-auto">
@auth
@if(auth()->user()->role === 'master')<a class="btn btn-outline-light btn-sm" href="{{ route('admin.dashboard') }}">Panel maestro</a>@else<a class="btn btn-outline-light btn-sm" href="{{ route('dashboard') }}">Mi panel</a>@endif
<form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn-light btn-sm">Cerrar sesión</button></form>
@else<a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Iniciar sesión</a><a class="btn btn-primary btn-sm" href="{{ route('register') }}">Registrarme</a>@endauth
</div></div></div></nav>
<main>@yield('content')</main>
<footer class="border-top mt-5 py-4"><div class="container text-secondary small">Sport Club</div></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
