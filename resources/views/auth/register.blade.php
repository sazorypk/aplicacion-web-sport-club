@extends('layouts.public')
@section('title', 'Crear cuenta')
@section('content')
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-8 col-lg-6"><div class="card shadow-sm"><div class="card-body p-4"><h1 class="h3 mb-4">Crear cuenta</h1>
<form method="POST" action="{{ route('register') }}">@csrf
<div class="mb-3"><label class="form-label" for="name">Nombre completo</label><input class="form-control" id="name" name="name" value="{{ old('name') }}" required autocomplete="name">@error('name')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="username">Nombre de usuario</label><input class="form-control" id="username" name="username" value="{{ old('username') }}" required maxlength="255" autocomplete="username">@error('username')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="cedula">Cédula</label><input class="form-control" id="cedula" name="cedula" value="{{ old('cedula') }}" required>@error('cedula')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="phone">Teléfono</label><input class="form-control" id="phone" name="phone" type="tel" value="{{ old('phone') }}" required autocomplete="tel">@error('phone')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="email">Correo electrónico</label><input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email">@error('email')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="password">Contraseña</label><input class="form-control" id="password" name="password" type="password" required autocomplete="new-password">@error('password')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="password_confirmation">Confirmar contraseña</label><input class="form-control" id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"></div>
<div class="form-check mb-3"><input class="form-check-input" type="checkbox" id="privacy_consent" name="privacy_consent" value="1" @checked(old('privacy_consent')) required><label class="form-check-label" for="privacy_consent">Autorizo el tratamiento de mis datos personales para gestionar mi cuenta y membresía, conforme a la <a href="{{ route('privacy.policy') }}" target="_blank" rel="noopener noreferrer">política de privacidad</a>.</label>@error('privacy_consent')<div class="text-danger">{{ $message }}</div>@enderror</div>
<button class="btn btn-primary">Registrarme</button> <a class="btn btn-outline-secondary" href="{{ route('login') }}">Ya tengo cuenta</a>
</form></div></div></div></div></div>
@endsection
