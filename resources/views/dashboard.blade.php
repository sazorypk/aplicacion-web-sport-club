@extends('layouts.public')

@section('title', 'Bienvenido')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h1 class="h2">Bienvenido/a, {{ auth()->user()->name }}</h1>
                    <p class="lead text-secondary">Tu cuenta de Sport Club ya esta lista.</p>

                    @if(auth()->user()->role === 'master')
                        <p>Desde el panel maestro puedes administrar los planes y consultar los miembros registrados.</p>
                        <a class="btn btn-primary" href="{{ route('admin.dashboard') }}">Ir al panel maestro</a>
                    @else
                        <p>El siguiente paso es escoger el plan y la sede que prefieres.</p>
                        <div class="d-flex gap-2 flex-wrap">
                            <a class="btn btn-primary" href="{{ route('mi-membresia.create') }}">Escoger un plan</a>
                            <a class="btn btn-outline-primary" href="{{ route('mi-membresia.index') }}">Ver mi membresia</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection