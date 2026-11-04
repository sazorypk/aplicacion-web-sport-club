@extends('layouts.public')

@section('title', 'Disciplinas | Sport Club')

@section('content')
<section class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold">Encuentra tu disciplina</h1>
        <p class="lead">Explora nuestras modalidades deportivas.</p>
    </div>

    <div class="row g-4">
        @foreach ($disciplinas as $disciplina)
            <div class="col-md-6 col-lg-4">
                <article class="card h-100">
                    <img
                        src="{{ asset('assets/img/' . $disciplina['imagen']) }}"
                        class="card-img-top"
                        alt="Entrenamiento de {{ $disciplina['nombre'] }}">

                    <div class="card-body">
                        <h2 class="card-title h4">{{ $disciplina['nombre'] }}</h2>
                        <p class="card-text">{{ $disciplina['descripcion'] }}</p>
                    </div>
                </article>
            </div>
        @endforeach
    </div>
</section>
@endsection