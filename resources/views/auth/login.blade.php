@extends('layouts.public')
@section('title', 'Iniciar sesión')
@section('content')
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-7 col-lg-5"><div class="card shadow-sm"><div class="card-body p-4"><h1 class="h3 mb-4">Iniciar sesión</h1>
@if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
<form method="POST" action="{{ route('login') }}">@csrf
<div class="mb-3"><label class="form-label" for="username">Nombre de usuario</label><input class="form-control" id="username" name="username" type="text" value="{{ old('username') }}" required autofocus autocomplete="username">@error('username')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="password">Contraseña</label><input class="form-control" id="password" name="password" type="password" required autocomplete="current-password">@error('password')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="form-check mb-3"><input class="form-check-input" id="remember" type="checkbox" name="remember"><label class="form-check-label" for="remember">Recordarme</label></div>
<button class="btn btn-primary">Entrar</button> <a class="btn btn-outline-secondary" href="{{ route('register') }}">Crear cuenta</a>
@if(Route::has('password.request'))<div class="mt-3"><a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a></div>@endif
</form></div></div></div></div></div>
@endsection
