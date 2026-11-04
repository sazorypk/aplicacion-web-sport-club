@extends('layouts.public')
@section('title', 'Miembros registrados')
@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="small text-decoration-none">← Volver al panel</a>
            <h1 class="h2 mt-2 mb-1">Miembros registrados</h1>
            <p class="text-secondary mb-0">Consulta sus datos y gestiona sus membresías y cuentas.</p>
        </div>
        <span class="badge rounded-pill text-bg-light border px-3 py-2">{{ $members->total() }} miembros</span>
    </div>
    @include('shared.flash')
    <div class="d-grid gap-3">
        @forelse($members as $member)
            <article class="card shadow-sm">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h2 class="h5 mb-1">{{ $member->name }}</h2>
                            <p class="text-secondary small mb-0">{{ $member->email }}</p>
                        </div>
                        @if($member->role === 'master')<span class="badge rounded-pill text-bg-dark">Cuenta maestra</span>@endif
                    </div>
                    <div class="row g-2 small text-secondary mb-4">
                        <div class="col-sm-6"><strong class="text-dark">Documento:</strong> {{ $member->cedula ?: 'No registrado' }}</div>
                        <div class="col-sm-6"><strong class="text-dark">Teléfono:</strong> {{ $member->phone ?: 'No registrado' }}</div>
                    </div>
                    <h3 class="h6 mb-3">Membresías <span class="text-secondary fw-normal">({{ $member->memberships->count() }})</span></h3>
                    <div class="d-grid gap-2">
                        @forelse($member->memberships as $membership)
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                    <div><strong>{{ $membership->plan->name }}</strong><div class="small text-secondary">{{ $membership->sede->name }}</div></div>
                                    <span class="badge rounded-pill text-bg-{{ match ($membership->status) { 'active' => 'success', 'pending' => 'warning', default => 'secondary' } }}">{{ match ($membership->status) { 'active' => 'Activa', 'pending' => 'Pendiente', 'cancelled' => 'Cancelada', default => $membership->status } }}</span>
                                </div>
                                @if(in_array($membership->status, ['pending', 'active'], true))
                                    <div class="d-flex flex-wrap align-items-center gap-2 mt-3">
                                        <form method="POST" action="{{ route('admin.membresias.cancelar', $membership) }}" onsubmit="return confirm('¿Deseas cancelar esta membresía? Se conservarán sus pagos.')">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-danger" type="submit">Cancelar membresía</button></form>
                                        <span class="small text-secondary">La cancelación conserva la cuenta y los pagos.</span>
                                    </div>
                                @elseif($membership->status === 'cancelled')
                                    <details class="mt-3">
                                        <summary class="small text-danger" style="cursor: pointer">Opciones de eliminación</summary>
                                        <p class="small text-secondary mt-2 mb-2">Eliminar esta membresía también borra sus pagos asociados de forma permanente.</p>
                                        <form method="POST" action="{{ route('admin.membresias.destroy', $membership) }}" onsubmit="return confirm('¿Eliminar definitivamente esta membresía y todos sus pagos asociados? Esta acción no se puede deshacer.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" type="submit">Eliminar membresía</button></form>
                                    </details>
                                @endif
                            </div>
                        @empty
                            <p class="text-secondary small mb-0">Este usuario todavía no tiene membresías.</p>
                        @endforelse
                    </div>
                    @if($member->role !== 'master')
                        <details class="border-top mt-4 pt-3">
                            <summary class="small fw-semibold" style="cursor: pointer">Gestionar cuenta</summary>
                            @if($member->memberships->isNotEmpty())
                                <p class="small text-secondary mt-2 mb-0">Para eliminar la cuenta, primero cancela las membresías activas o pendientes y después elimina cada membresía cancelada. Al eliminar una membresía también se borran sus pagos. Si solo deseas conservar el historial, no necesitas eliminar la cuenta.</p>
                            @else
                                <p class="small text-secondary mt-2 mb-2">Este usuario no tiene membresías. Puedes eliminar su cuenta de forma permanente.</p>
                                <form method="POST" action="{{ route('admin.miembros.destroy', $member) }}" onsubmit="return confirm('¿Eliminar definitivamente esta cuenta de usuario? Esta acción no se puede deshacer.')">@csrf @method('DELETE')<button class="btn btn-sm btn-danger" type="submit">Eliminar usuario</button></form>
                            @endif
                        </details>
                    @endif
                </div>
            </article>
        @empty
            <div class="alert alert-light border">No hay usuarios registrados.</div>
        @endforelse
    </div>
    <div class="mt-4">{{ $members->links() }}</div>
</div>
@endsection
