@extends('layouts.public')

@section('title', 'Nosotros | Sport Club')

@section('content')
<section class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h1 class="fw-bold">Más que un club, una pasión que nos une</h1>

            <p class="lead">
                En Sport Club promovemos el bienestar, la disciplina y el
                desarrollo deportivo. Contamos con distintas modalidades,
                instalaciones modernas y entrenadores especializados.
            </p>

            <p>
                Nuestro propósito es crear una comunidad donde cada persona
                encuentre un espacio para entrenar, superar sus límites y
                disfrutar del deporte.
            </p>
        </div>

        <div class="col-lg-6">
            <img
                src="{{ asset('assets/img/nosotros.webp') }}"
                class="img-fluid rounded-4"
                alt="Comunidad deportiva de Sport Club">
        </div>
    </div>
</section>
@endsection