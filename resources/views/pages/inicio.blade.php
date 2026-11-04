@extends('layouts.public')

@section('title', 'Inicio | Sport Club')

@section('content')
<section class="container py-5">
    <div class="row align-items-center g-4">
        <div class="col-lg-6">
            <h1 class="display-4 fw-bold">
                Un solo club e infinitas formas de superarte
            </h1>

            <p class="lead">
                Entrena, mejora tus habilidades y alcanza tus objetivos
                junto a nuestra comunidad deportiva.
            </p>

            <a href="{{ route('login') }}" class="btn btn-danger btn-lg">
                Únete ahora
            </a>
        </div>

        <div class="col-lg-6">
            <img
                src="{{ asset('assets/img/hero.webp') }}"
                class="img-fluid rounded-4"
                alt="Deporte en Sport Club">
        </div>
    </div>
</section>
@endsection