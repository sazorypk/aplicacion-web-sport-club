@extends('layouts.public')
@section('title', 'Mi membresía')
@section('content')<div class="container py-5"><div class="d-flex justify-content-between align-items-center mb-4"><h1>Mi membresía</h1>@if(!$memberships->contains(fn($m) => in_array($m->status, ['pending','active'])))<a class="btn btn-primary" href="{{ route('mi-membresia.create') }}">Elegir plan</a>@endif</div>@include('shared.flash')
@forelse($memberships as $membership)<div class="card mb-3"><div class="card-body"><div class="d-flex justify-content-between flex-wrap"><h2 class="h5">{{ $membership->plan->name }} · {{ $membership->sede->name }}</h2><span class="badge text-bg-{{ $membership->status === 'active' ? 'success' : ($membership->status === 'pending' ? 'warning' : 'secondary') }}">{{ match ($membership->status) { 'active' => 'Activa', 'pending' => 'Pendiente', 'cancelled' => 'Cancelada', default => ucfirst($membership->status) } }}</span></div>@if($membership->plan->includes_all_disciplines)<p>Incluye todas las disciplinas.</p>@elseif($membership->disciplines)<p>Disciplinas: {{ implode(', ', $membership->disciplines) }}</p>@elseif($membership->discipline)<p>Disciplina: {{ $membership->discipline }}</p>@else<p>Disciplinas pendientes de selección.</p>@endif
@if(!$membership->plan->includes_all_disciplines && in_array($membership->status, ['active', 'pending'], true) && count($membership->disciplines ?? ($membership->discipline ? [$membership->discipline] : [])) < $membership->plan->discipline_limit)
<form method="POST" action="{{ route('mi-membresia.disciplinas', $membership) }}" class="border rounded p-3 mb-3">
    @csrf @method('PATCH')
    <fieldset>
        <legend class="fs-6">Completa tu selección de disciplinas</legend>
        <p class="small text-secondary">Tu plan incluye {{ $membership->plan->discipline_limit }} disciplinas. Selecciona esa cantidad para completar tu inscripción.</p>
        @foreach(\App\Models\Membership::DISCIPLINES as $discipline)
        <div class="form-check"><input class="form-check-input" type="checkbox" id="membership-{{ $membership->id }}-discipline-{{ $loop->index }}" name="disciplines[]" value="{{ $discipline }}" @checked(in_array($discipline, (array) old('disciplines', $membership->disciplines ?? ($membership->discipline ? [$membership->discipline] : [])), true))><label class="form-check-label" for="membership-{{ $membership->id }}-discipline-{{ $loop->index }}">{{ $discipline }}</label></div>
        @endforeach
        @error('disciplines')<div class="text-danger small">{{ $message }}</div>@enderror
        @error('disciplines.*')<div class="text-danger small">{{ $message }}</div>@enderror
        <button class="btn btn-sm btn-primary mt-2" type="submit">Guardar disciplinas</button>
    </fieldset>
</form>
@endif
<p>Precio registrado: ${{ number_format($membership->price_at_signup, 0, ',', '.') }} COP</p>@if($membership->start_date)<p class="mb-2">Vigencia: {{ $membership->start_date->format('d/m/Y') }} – {{ $membership->end_date?->format('d/m/Y') }}</p>@endif
@if($membership->status === 'pending')<form method="POST" action="{{ route('mi-membresia.pago-simulado', $membership) }}">@csrf<button class="btn btn-success">Simular pago y activar</button></form>@endif
@if($membership->payments->isNotEmpty())<p class="mb-0 mt-3">Pago: {{ ucfirst($membership->payments->last()->status) }} · Ref. {{ $membership->payments->last()->reference }}</p>@endif</div></div>@empty<div class="alert alert-info">Todavía no tienes una membresía. Puedes elegir un plan cuando quieras.</div>@endforelse</div>@endsection
