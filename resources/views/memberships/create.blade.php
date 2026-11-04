@extends('layouts.public')
@section('title', 'Elegir membresía')
@section('content')<div class="container py-5"><h1 class="mb-2">Elige tu plan y sede</h1>@include('shared.flash')
<form method="POST" action="{{ route('mi-membresia.store') }}">@csrf
<div class="mb-3"><label class="form-label" for="plan_id">Plan</label><select class="form-select" id="plan_id" name="plan_id" required><option value="">Selecciona un plan</option>@foreach($plans as $plan)<option value="{{ $plan->id }}" @selected(old('plan_id') == $plan->id)>{{ $plan->name }} — ${{ number_format($plan->price, 0, ',', '.') }}/mes</option>@endforeach</select>@error('plan_id')<div class="text-danger">{{ $message }}</div>@enderror</div>
<div class="mb-3"><label class="form-label" for="sede_id">Sede</label><select class="form-select" id="sede_id" name="sede_id" required><option value="">Selecciona una sede</option>@foreach($sedes as $sede)<option value="{{ $sede->id }}" @selected(old('sede_id') == $sede->id)>{{ $sede->name }}</option>@endforeach</select>@error('sede_id')<div class="text-danger">{{ $message }}</div>@enderror</div>
<button class="btn btn-primary">Continuar</button></form></div>@endsection
