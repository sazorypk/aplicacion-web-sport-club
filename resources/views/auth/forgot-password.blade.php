@extends('layouts.public')

@section('title', 'Recuperar contraseña | Sport Club')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-3">Recuperar contraseña</h1>
                    <p class="text-secondary">Ingresa el correo de tu cuenta y te enviaremos un enlace para crear una nueva contraseña.</p>
                    @if (session('status'))
                        <div class="alert alert-success" role="status">{{ session('status') }}</div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo electrónico</label>
                            <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Enviar enlace de recuperación</button>
                        <a class="btn btn-outline-secondary" href="{{ route('login') }}">Volver a iniciar sesión</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
