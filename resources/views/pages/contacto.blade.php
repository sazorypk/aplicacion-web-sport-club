@extends('layouts.public')

@section('title', 'Contacto | Sport Club')

@section('content')
<section class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-7">
            <h1 class="fw-bold">¿Listo para superar tus límites?</h1>

            <p class="lead">
                Da el primer paso y forma parte de nuestra comunidad deportiva.
                Contáctanos para conocer las disciplinas, instalaciones y planes.
            </p>

            <h2 class="h4 mt-4">Comunícate con nosotros</h2>
            <p>WhatsApp: +57 321 786 8266</p>

            <a
                href="https://wa.me/573217868266"
                class="btn btn-danger btn-lg"
                target="_blank"
                rel="noopener noreferrer">
                Escríbenos por WhatsApp
            </a>
        </div>

        <div class="col-lg-5">
            <div class="p-4 bg-light rounded-4">
                <h2 class="h4">Sport Club</h2>
                <p class="mb-0">
                    Más que un club, una comunidad donde el deporte y la pasión nos unen.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection