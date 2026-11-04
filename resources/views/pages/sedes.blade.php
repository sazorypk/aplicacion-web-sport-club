@extends('layouts.marketing')
@section('title', 'Sedes y horarios | Sport Club')
@section('content')
<section class="locations-hero">
    <span class="eyebrow">SIEMPRE CERCA DE TI</span>
    <h1>ENCUENTRA TU LUGAR<br><span>PARA SUPERARTE.</span></h1>
    <p>Dos espacios, una misma pasión. Descubre nuestras sedes y encuentra el momento perfecto para entrenar.</p>
    <a href="#nuestras-sedes" class="hero__button">Conoce nuestras sedes ↓</a>
</section>
<section class="locations-section" id="nuestras-sedes">
    <div class="locations-heading"><div><span class="eyebrow">NORTE Y SUR DEL VALLE DE ABURRÁ</span><h2>NUESTRAS SEDES</h2></div><p>Abiertas todos los días para acompañarte en cada paso.</p></div>
    <p class="location-notice">Direcciones y horarios de ejemplo, sujetos a confirmación.</p>
    <div class="locations-grid">
        @forelse($sedes as $sede)
        <article class="location-card">
            <div class="location-card__top"><span class="location-card__number">0{{ $loop->iteration }} / SPORT CLUB</span><span class="location-card__tag">{{ $sede->name === 'Sede Norte' ? 'BELLO' : ($sede->name === 'Sede Sur' ? 'ENVIGADO' : 'ANTIOQUIA') }}</span></div>
            <h3>{{ $sede->name }}</h3>
            <p class="location-card__label">DIRECCIÓN</p>
            <p class="location-card__address">{{ $sede->address ?: 'Dirección por confirmar' }}</p>
            <div class="location-card__hours"><p class="location-card__label">HORARIOS DE ATENCIÓN</p><div><span>Lunes a viernes</span><strong>5:00 a. m. – 10:00 p. m.</strong></div><div><span>Sábados</span><strong>7:00 a. m. – 6:00 p. m.</strong></div><div><span>Domingos</span><strong>8:00 a. m. – 2:00 p. m.</strong></div></div>
            <a class="location-card__link" href="https://wa.me/573217868266" target="_blank" rel="noopener noreferrer">Consulta por esta sede <span>↗</span></a>
        </article>
        @empty
        <p>Pronto publicaremos nuestras sedes.</p>
        @endforelse
    </div>
</section>
<section class="location-cta"><div><span class="eyebrow">TU SIGUIENTE PASO</span><h2>ELIGE CÓMO QUIERES ENTRENAR</h2><p>Encuentra un plan para tus objetivos y comienza a moverte.</p></div><a class="hero__button" href="{{ route('planes') }}">Ver planes →</a></section>
@endsection
