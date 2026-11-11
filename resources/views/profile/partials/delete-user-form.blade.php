<section>
    <h2 class="h4">Solicitar eliminación de cuenta</h2>
    <p class="text-secondary">Tu cuenta permanecerá activa mientras el responsable revisa tu solicitud. Al atenderla se desactivará el acceso, se sustituirán tus datos de identificación y se cancelarán las membresías activas o pendientes; los registros de membresías y pagos simulados se conservarán para el proyecto. Puedes escribir a <a href="mailto:privacidad@correo.sportclub.elnexusdigital.com">privacidad@correo.sportclub.elnexusdigital.com</a>.</p>
    @if (session('deletion_request_status'))<div class="alert alert-success" role="status">{{ session('deletion_request_status') }}</div>@endif
    @if ($errors->deletionRequest->any())<div class="alert alert-danger" role="alert">{{ $errors->deletionRequest->first() }}</div>@endif
    @if ($deletionRequest)
        <div class="alert alert-info">Tu solicitud está {{ $deletionRequest->status === 'in_review' ? 'en revisión' : 'pendiente de revisión' }}.</div>
    @else
        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirm-deletion-request">Solicitar eliminación</button>
        <div class="modal fade" id="confirm-deletion-request" tabindex="-1" aria-labelledby="deletion-request-title" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
                <form method="POST" action="{{ route('profile.deletion-request.store') }}">
                    @csrf
                    <div class="modal-header"><h2 class="modal-title fs-5" id="deletion-request-title">Confirmar solicitud</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button></div>
                    <div class="modal-body"><p>Esto inicia una revisión; no elimina la cuenta de inmediato. Ingresa tu contraseña para confirmar.</p><label class="form-label" for="deletion_request_password">Contraseña</label><input id="deletion_request_password" name="password" type="password" class="form-control" required autocomplete="current-password"></div>
                    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" class="btn btn-danger">Enviar solicitud</button></div>
                </form>
            </div></div>
        </div>
    @endif
</section>
