@extends('layouts.public')
@section('title', 'Administrar planes')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4"><h1>Planes</h1><a class="btn btn-primary" href="{{ route('admin.planes.create') }}">Nuevo plan</a></div>
    @include('shared.flash')
    <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Nombre</th><th>Precio</th><th>Estado</th><th>Inscripciones</th><th></th></tr></thead><tbody>
    @forelse($plans as $plan)<tr><td>{{ $plan->name }}</td><td>${{ number_format($plan->price, 0, ',', '.') }}</td><td>{{ $plan->is_active ? 'Activo' : 'Inactivo' }}</td><td>{{ $plan->memberships_count }}</td><td class="text-end"><a class="btn btn-sm btn-outline-primary" href="{{ route('admin.planes.edit', $plan) }}">Editar</a> <form class="d-inline" method="POST" action="{{ route('admin.planes.destroy', $plan) }}" onsubmit="return confirm('¿Eliminar este plan?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Eliminar</button></form></td></tr>@empty<tr><td colspan="5">Aún no hay planes.</td></tr>@endforelse
    </tbody></table></div>{{ $plans->links() }}
</div>
@endsection
