@extends('layouts.public')
@section('title', 'Miembros registrados')
@section('content')
<div class="container py-5"><h1 class="mb-4">Miembros registrados</h1>@include('shared.flash')<div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Documento</th><th>Nombre</th><th>Correo</th><th>Contacto</th><th>Plan</th><th>Sede</th><th>Estado</th><th>Acciones</th></tr></thead><tbody>
@forelse($members as $member)
@php($membership = $member->memberships->first(fn ($item) => in_array($item->status, ['pending', 'active'], true)) ?? $member->memberships->first())
<tr><td>{{ $member->cedula ?: '—' }}</td><td>{{ $member->name }}</td><td>{{ $member->email }}</td><td>{{ $member->phone ?: '—' }}</td><td>{{ $membership?->plan?->name ?? 'Sin inscripción' }}</td><td>{{ $membership?->sede?->name ?? '—' }}</td><td>{{ match ($membership?->status) { 'active' => 'Activa', 'pending' => 'Pendiente', 'cancelled' => 'Cancelada', null => '—', default => $membership->status } }}</td><td>
@if($membership && in_array($membership->status, ['pending', 'active'], true))
<form method="POST" action="{{ route('admin.membresias.cancelar', $membership) }}" onsubmit="return confirm('¿Deseas cancelar esta membresía?')">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger" type="submit">Cancelar membresía</button></form>
@else — @endif
</td></tr>@empty<tr><td colspan="8">No hay usuarios registrados.</td></tr>@endforelse
</tbody></table></div>{{ $members->links() }}</div>
@endsection
