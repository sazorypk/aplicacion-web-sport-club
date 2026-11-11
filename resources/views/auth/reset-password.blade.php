@extends('layouts.public')

@section('title', 'Crear nueva contraseña | Sport Club')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4">Crear nueva contraseña</h1>
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="mb-3">
                            <label class="form-label" for="email">Correo electrónico</label>
                            <input class="form-control" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email">
                            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password">Nueva contraseña</label>
                            <input class="form-control" id="password" type="password" name="password" required autocomplete="new-password">
                            @error('password')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                            <input class="form-control" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                            @error('password_confirmation')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>
                        <button class="btn btn-primary" type="submit">Guardar contraseña</button>
                        <a class="btn btn-outline-secondary" href="{{ route('login') }}">Volver a iniciar sesión</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
