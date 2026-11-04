@extends('layouts.public')
@section('title', 'Miembros registrados')
@section('content')
<div class="container py-5">
    <h1 class="mb-2">Miembros registrados</h1>
    <p class="text-secondary">Cancela una membresía para conservar su historial, o elimínala junto con sus pagos antes de borrar al usuario.</p>
    @include('shared.flash')
    <div class="table-responsive">
        <table class="table table-striped align-middle admin-members-table">
            <thead><tr><th>Documento</th><th>Nombre</th><th>Correo</th><th>Contacto</th><th>Membresías</th><th>Usuario</th></tr></thead>
            <tbody>
                @forelse($members as $member)
                    <tr>
                        <td data-label="Documento">{{ $member->cedula ?: '—' }}</td>
                        <td data-label="Nombre">{{ $member->name }}</td>
                        <td data-label="Correo">{{ $member->email }}</td>
                        <td data-label="Contacto">{{ $member->phone ?: '—' }}</td>
                        <td data-label="Membresías">
                            @forelse($member->memberships as $membership)
                                <div class="border rounded p-2 mb-2">
                                    <div><strong>{{ $membership->plan->name }}</strong> · {{ $membership->sede->name }}</div>
                                    <div class="small mb-2">{{ match ($membership->status) { 'active' => 'Activa', 'pending' => 'Pendiente', 'cancelled' => 'Cancelada', default => $membership->status } }}</div>
                                    @if(in_array($membership->status, ['pending', 'active'], true))
                                        <form method="POST" action="{{ route('admin.membresias.cancelar', $membership) }}" onsubmit="return confirm('¿Deseas cancelar esta membresía? Se conservarán sus pagos.')">
                                            @csrf @method('PATCH')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Cancelar membresía</button>
                                        </form>
                                    @elseif($membership->status === 'cancelled')
                                        <form method="POST" action="{{ route('admin.membresias.destroy', $membership) }}" onsubmit="return confirm('¿Eliminar definitivamente esta membresía y todos sus pagos asociados? Esta acción no se puede deshacer.')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" type="submit">Eliminar membresía</button>
                                        </form>
                                    @endif
                                </div>
                            @empty
                                <span class="text-secondary">Sin inscripción</span>
                            @endforelse
                        </td>
                        <td data-label="Usuario">
                            @if($member->role === 'master')
                                <span class="text-secondary small">Cuenta maestra</span>
                            @elseif($member->memberships->isNotEmpty())
                                <span class="text-secondary small">Elimina primero todas sus membresías.</span>
                            @else
                                <form method="POST" action="{{ route('admin.miembros.destroy', $member) }}" onsubmit="return confirm('¿Eliminar definitivamente esta cuenta de usuario? Esta acción no se puede deshacer.')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" type="submit">Eliminar usuario</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">No hay usuarios registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $members->links() }}
</div>
@endsection
