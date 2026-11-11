@extends('layouts.public')

@section('title', 'Panel maestro')

@section('content')
<div class="container py-5">
    <h1 class="mb-2">Panel maestro</h1>
    <p class="text-secondary">Administra los planes y consulta las personas registradas.</p>
    <div class="d-flex gap-2 flex-wrap mt-4">
        <a class="btn btn-primary" href="{{ route('admin.planes.index') }}">Administrar planes</a>
        <a class="btn btn-outline-primary" href="{{ route('admin.miembros.index') }}">Ver miembros</a>
        <a class="btn btn-outline-primary" href="{{ route('admin.privacy-requests.index') }}">Solicitudes de privacidad</a>
    </div>
</div>
@endsection
