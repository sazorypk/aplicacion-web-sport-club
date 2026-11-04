@extends('layouts.marketing')
@section('title', 'Inicio | Sport Club')
@section('content')
<!-- 1. HERO SECTION -->
        <section class="hero" id="inicio">

            <div class="hero__content">
                <h1 class="hero__title">UN SOLO CLUB E INFINITAS FORMAS DE SUPERARTE.</h1>
                <p class="hero__description">Descubre un espacio donde el deporte, la disciplina y la pasión se encuentran. Entrena diferentes modalidades, mejora tus habilidades y alcanza tus objetivos junto a profesionales.</p>
                <a href="{{ route('disciplinas') }}" class="hero__button">Ver disciplinas</a>
            </div>
</section>
<!-- 2. SECCIÓN NOSOTROS -->
        <section class="nosotros" id="nosotros">

            <div class="nosotros__content">
                <h2 class="nosotros__title">MÁS QUE UN CLUB, UNA PASIÓN QUE NOS UNE</h2>
                <p class="nosotros__description">En Sport Club somos un centro multideportivo dedicado a promover el bienestar, la disciplina y el desarrollo de nuestros deportistas. Contamos con diferentes modalidades deportivas, instalaciones modernas y entrenadores especializados que acompañan a niños, jóvenes y adultos en cada etapa de su formación. Nuestro propósito es crear una comunidad donde cada persona encuentre un espacio para entrenar, superar sus límites y disfrutar del deporte.</p>
                <a href="{{ route('disciplinas') }}" class="nosotros__button">Explora nuestras disciplinas</a>
            </div>

            <div class="nosotros__image">
                <img src="{{ asset('assets/img/nosotros.webp') }}" alt="Comunidad deportiva de Sport Club" class="nosotros__image-file" loading="lazy">
            </div>
        </section>
<!-- 4. SECCIÓN PLANES -->
        <section class="planes" id="planes">

            <div class="planes__header">
                <h2 class="planes__title">ELIGE TU PLAN IDEAL</h2>
                <p class="planes__description">Tenemos diferentes alternativas para que puedas disfrutar del deporte a tu ritmo.</p>
            </div>

            <div class="planes__grid">

                @forelse ($plans as $plan)
                <article class="plan-card {{ $loop->iteration === 2 ? 'plan-card--featured' : '' }}">
                    @if ($loop->iteration === 2)<span class="plan-card__badge">Destacado</span>@endif
                    <h3 class="plan-card__title">{{ $plan->name }}</h3>
                    <p class="plan-card__price">${{ number_format((float) $plan->price, 0, ',', '.') }} <span>/ mes</span></p>
                    <p class="plan-card__description">{{ $plan->description }}</p>
                    <a href="{{ route('mi-membresia.create') }}" class="plan-card__button">Elegir plan</a>
                </article>
                @empty
                <p>Pronto publicaremos nuestros planes.</p>
                @endforelse

            </div>
        </section>
<!-- TESTIMONIOS -->
        <section class="testimonios" id="testimonios">
            <div class="testimonios__header">
                <h2 class="testimonios__title">LO QUE DICE NUESTRA COMUNIDAD</h2>
                <p class="testimonios__description">Historias de personas que entrenan, crecen y superan sus metas en Sport Club.</p>
            </div>
            <div class="testimonios__grid">
                <article class="testimonio-card">
                    <div class="testimonio-card__avatar" aria-hidden="true">CM</div>
                    <p class="testimonio-card__quote">“Encontré un espacio donde puedo entrenar y compartir mi pasión por el deporte. Los entrenadores siempre están pendientes de mi progreso.”</p>
                    <h3 class="testimonio-card__name">Camila Martínez</h3>
                    <p class="testimonio-card__role">Deportista de natación</p>
                </article>
                <article class="testimonio-card testimonio-card--featured">
                    <div class="testimonio-card__avatar" aria-hidden="true">JR</div>
                    <p class="testimonio-card__quote">“Desde que llegué al club mejoré mi técnica y encontré un equipo que me motiva a dar lo mejor en cada entrenamiento.”</p>
                    <h3 class="testimonio-card__name">Juan Rodríguez</h3>
                    <p class="testimonio-card__role">Jugador de fútbol</p>
                </article>
                <article class="testimonio-card">
                    <div class="testimonio-card__avatar" aria-hidden="true">LP</div>
                    <p class="testimonio-card__quote">“Las instalaciones y la variedad de disciplinas me ayudan a mantenerme activa y disfrutar cada sesión.”</p>
                    <h3 class="testimonio-card__name">Laura Pérez</h3>
                    <p class="testimonio-card__role">Miembro del club</p>
                </article>
            </div>
        </section>
<section class="location-cta"><div><span class="eyebrow">ENTRENA CERCA DE TI</span><h2>ENCUENTRA TU LUGAR EN EL CLUB</h2><p>Conoce nuestras sedes en Bello y Envigado y encuentra el horario que se adapta a ti.</p></div><a class="hero__button" href="{{ route('sedes') }}">Ver sedes y horarios →</a></section>
@endsection
