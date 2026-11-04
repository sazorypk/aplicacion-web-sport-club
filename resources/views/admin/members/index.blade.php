@extends('layouts.public')
@section('title', 'Miembros registrados')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="small text-decoration-none">← Volver al panel</a>
            <h1 class="h2 mt-2 mb-1">Miembros registrados</h1>
            <p class="text-secondary mb-0">Consulta los datos y gestiona las membresías de cada persona.</p>
        </div>
        <span class="badge rounded-pill text-bg-light border px-3 py-2">{{ $members->total() }} miembros</span>
    </div>
    @include('shared.flash')
    <div class="table-responsive">
        <table class="table table-hover align-middle admin-members-table">
            <thead><tr><th scope="col">Miembro</th><th scope="col">Documento</th><th scope="col">Contacto</th><th scope="col">Membresía</th><th scope="col">Acciones</th></tr></thead>
            <tbody>
                @forelse($members as $member)
                    @php($currentMembership = $member->memberships->first(fn ($item) => in_array($item->status, ['active', 'pending'], true)) ?? $member->memberships->first())
                    <tr>
                        <td data-label="Miembro"><div><strong>{{ $member->name }}</strong>@if($member->is(auth()->user())) <span class="badge text-bg-light border">Tu cuenta</span>@elseif($member->role === 'master') <span class="badge text-bg-light border">Cuenta maestra</span>@endif<div class="small text-secondary">{{ $member->email }}</div></div></td>
                        <td data-label="Documento">{{ $member->cedula ?: '—' }}</td>
                        <td data-label="Contacto">{{ $member->phone ?: '—' }}</td>
                        <td data-label="Membresía">
                            @if($currentMembership)
                                <div><span>{{ $currentMembership->plan->name }}</span><div class="small text-secondary">{{ match ($currentMembership->status) { 'active' => 'Activa', 'pending' => 'Pendiente', 'cancelled' => 'Cancelada', default => $currentMembership->status } }}</div></div>
                            @else
                                <span class="text-secondary">Sin membresía</span>
                            @endif
                        </td>
                        <td data-label="Acciones">
                            @if($member->memberships->isNotEmpty() || $member->role !== 'master')
                                <details>
                                    <summary class="btn btn-sm btn-outline-primary">Gestionar <span class="visually-hidden">a {{ $member->name }}</span></summary>
                                    <div class="border rounded bg-light p-3 mt-2">
                                        @foreach($member->memberships as $membership)
                                            <div class="mb-3">
                                                <strong>{{ $membership->plan->name }}</strong> · {{ $membership->sede->name }}
                                                <div class="small text-secondary">{{ match ($membership->status) { 'active' => 'Activa', 'pending' => 'Pendiente', 'cancelled' => 'Cancelada', default => $membership->status } }}</div>
                                                @if(in_array($membership->status, ['pending', 'active'], true))
                                                    <form class="mt-2" method="POST" action="{{ route('admin.membresias.cancelar', $membership) }}" onsubmit="return confirm('¿Deseas cancelar esta membresía? Se conservarán sus pagos.')">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger" type="submit">Cancelar membresía</button></form>
                                                @elseif($membership->status === 'cancelled')
                                                    <p class="small text-secondary mt-2 mb-1">Eliminar esta membresía también borra sus pagos de forma permanente.</p>
                                                    <form method="POST" action="{{ route('admin.membresias.destroy', $membership) }}" onsubmit="return confirm('¿Eliminar definitivamente esta membresía y todos sus pagos asociados? Esta acción no se puede deshacer.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Eliminar membresía</button></form>
                                                @endif
                                            </div>
                                        @endforeach
                                        @if($member->role !== 'master')
                                            @if($member->memberships->isNotEmpty())
                                                <p class="small text-secondary mb-0">Para borrar la cuenta, cancela y elimina cada membresía. Si solo la cancelas, conservas el historial.</p>
                                            @else
                                                <form method="POST" action="{{ route('admin.miembros.destroy', $member) }}" onsubmit="return confirm('¿Eliminar definitivamente esta cuenta de usuario? Esta acción no se puede deshacer.')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" type="submit">Eliminar usuario</button></form>
                                            @endif
                                        @endif
                                    </div>
                                </details>
                            @else
                                <span class="text-secondary small">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No hay usuarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $members->links() }}</div>
</div>
@endsection
