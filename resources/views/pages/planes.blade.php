@extends('layouts.public')

@section('title', 'Planes')

@section('content')
<div class="container py-5">
    <h1 class="text-center mb-2">Planes del club</h1>
    <p class="text-center text-secondary mb-5">Elige la opci&oacute;n que se ajuste a tus objetivos.</p>

    <div class="row g-4">
        @forelse($plans as $plan)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex flex-column">
                        <h2 class="h4">{{ $plan->name }}</h2>
                        <p class="text-secondary">{{ $plan->description }}</p>
                        <p class="fs-4 fw-bold"><span>$</span>{{ number_format((float) $plan->price, 0, ',', '.') }} <small class="fs-6 fw-normal">COP / mes</small></p>
                        <a class="btn btn-primary mt-auto" href="{{ route('mi-membresia.create') }}">Elegir este plan</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12"><div class="alert alert-info">Pronto publicaremos nuestros planes.</div></div>
        @endforelse
    </div>
</div>
@endsection