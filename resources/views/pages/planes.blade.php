@extends('layouts.marketing')
@section('title', 'Planes | Sport Club')
@section('content')
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
<section class="location-cta"><div><span class="eyebrow">ENTRENA CERCA DE TI</span><h2>ENCUENTRA TU LUGAR EN EL CLUB</h2><p>Conoce nuestras sedes en Bello y Envigado y encuentra el horario que se adapta a ti.</p></div><a class="hero__button" href="{{ route('sedes') }}">Ver sedes y horarios →</a></section>
@endsection
