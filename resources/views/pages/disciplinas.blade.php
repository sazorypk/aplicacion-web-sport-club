@extends('layouts.marketing')
@section('title', 'Disciplinas | Sport Club')
@section('content')
<!-- 3. SECCIÓN DISCIPLINAS -->
        <section class="disciplinas" id="disciplinas">

            <div class="disciplinas__header">
                <h2 class="disciplinas__title">ENCUENTRA TU DISCIPLINA</h2>
                <p class="disciplinas__description">Explora nuestras modalidades deportivas y descubre nuevas formas de superar tus límites.</p>
            </div>

            <!-- CONTENEDOR DE TARJETAS -->
            <div class="disciplinas__grid">

                <!-- TARJETA 1 - FÚTBOL -->
                <article class="disciplina-card disciplina-card--featured">
                    <img src="{{ asset('assets/img/futbol.webp') }}" alt="Entrenamiento de fútbol" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Fútbol</h3>
                        <p class="disciplina-card__description">Desarrolla tu técnica, resistencia y trabajo en equipo en nuestras instalaciones.</p>
                        <a href="{{ route('contacto') }}" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 2 - BALONCESTO -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/baloncesto.webp') }}" alt="Entrenamiento de baloncesto" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Baloncesto</h3>
                        <p class="disciplina-card__description">Mejora tu coordinación, agilidad y habilidades mientras disfrutas del deporte en equipo.</p>
                        <a href="{{ route('contacto') }}" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 3 - NATACIÓN -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/natacion.webp') }}" alt="Entrenamiento de natación" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Natación</h3>
                        <p class="disciplina-card__description">Fortalece tu cuerpo y desarrolla nuevas habilidades dentro del agua.</p>
                        <a href="{{ route('contacto') }}" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 4 - ATLETISMO -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/atletismo.webp') }}" alt="Entrenamiento de atletismo" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Atletismo</h3>
                        <p class="disciplina-card__description">Supera tus marcas personales con entrenamientos enfocados en velocidad y resistencia.</p>
                        <a href="{{ route('contacto') }}" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 5 - VOLEIBOL -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/voleibol.webp') }}" alt="Entrenamiento de voleibol" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Voleibol</h3>
                        <p class="disciplina-card__description">Entrena tus reflejos, coordinación y comunicación mientras disfrutas de una disciplina en equipo.</p>
                        <a href="{{ route('contacto') }}" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 6 - GIMNASIO -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/gimnasio.webp') }}" alt="Entrenamiento en gimnasio" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Gimnasio</h3>
                        <p class="disciplina-card__description">Mejora tu condición física y alcanza tus objetivos con diferentes rutinas de entrenamiento.</p>
                        <a href="{{ route('contacto') }}" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

            </div>
        </section>
<section class="location-cta"><div><span class="eyebrow">ENTRENA CERCA DE TI</span><h2>ENCUENTRA TU LUGAR EN EL CLUB</h2><p>Conoce nuestras sedes en Bello y Envigado y encuentra el horario que se adapta a ti.</p></div><a class="hero__button" href="{{ route('sedes') }}">Ver sedes y horarios →</a></section>
@endsection
