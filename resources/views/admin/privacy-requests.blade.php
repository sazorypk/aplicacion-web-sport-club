@extends('layouts.public')
@section('title', 'Solicitudes de privacidad')
@section('content')
<div class="container py-5">
    <h1 class="mb-3">Solicitudes de eliminación</h1>
    <p class="text-secondary">Revisa cada solicitud y contacta al titular. La anonimización es irreversible: desactiva la cuenta y conserva las membresías y pagos simulados sin los datos directos del usuario. Revisa por separado posibles datos en correos, copias y registros de proveedores.</p>
    @include('shared.flash')
    @if ($errors->anonymization->any())<div class="alert alert-danger" role="alert">{{ $errors->anonymization->first() }}</div>@endif
    <div class="table-responsive"><table class="table table-striped align-middle"><thead><tr><th>Fecha</th><th>Usuario</th><th>Correo</th><th>Estado</th><th>Acción</th></tr></thead><tbody>
    @forelse($requests as $item)
        <tr><td>{{ $item->created_at->format('d/m/Y H:i') }}</td><td>{{ $item->user?->name ?? 'Cuenta no disponible' }}</td><td>{{ $item->status === 'completed' ? '—' : ($item->user?->email ?? '—') }}</td><td>{{ match($item->status) { 'pending' => 'Pendiente', 'in_review' => 'En revisión', 'completed' => 'Atendida', default => 'Desconocido' } }}</td><td>
            @if($item->status === 'pending')
                <form method="POST" action="{{ route('admin.privacy-requests.update', $item) }}">@csrf @method('PATCH')<button class="btn btn-sm btn-outline-primary">Marcar en revisión</button></form>
            @elseif($item->status === 'in_review' && $item->user && $item->user->role !== 'master')
                <form method="POST" action="{{ route('admin.privacy-requests.anonymize', $item) }}" onsubmit="return confirm('Esta acción es irreversible. ¿Anonimizar la cuenta y cancelar sus membresías activas o pendientes?')">
                    @csrf
                    <label class="form-label" for="admin-password-{{ $item->id }}">Tu contraseña</label>
                    <input class="form-control form-control-sm mb-2" id="admin-password-{{ $item->id }}" name="password" type="password" autocomplete="current-password" required>
                    <div class="form-check mb-2"><input class="form-check-input" id="confirm-{{ $item->id }}" name="confirm_anonymization" type="checkbox" value="1" required><label class="form-check-label" for="confirm-{{ $item->id }}">Confirmo la anonimización irreversible</label></div>
                    <button type="submit" class="btn btn-sm btn-outline-danger">Anonimizar cuenta</button>
                </form>
            @else — @endif
        </td></tr>
    @empty<tr><td colspan="5">No hay solicitudes de eliminación.</td></tr>@endforelse
    </tbody></table></div>
    {{ $requests->links() }}
</div>
@endsection
