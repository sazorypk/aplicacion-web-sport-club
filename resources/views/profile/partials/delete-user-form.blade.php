<section>
    <h2 class="h4">Eliminar cuenta</h2>
    <p class="text-secondary">Puedes eliminar tu cuenta si no tiene membresías registradas. La foto dejará de estar disponible y el almacenamiento puede conservar versiones ocultas por un tiempo.</p>
    @if ($errors->userDeletion->any())
        <div class="alert alert-danger" role="alert">{{ $errors->userDeletion->first() }}</div>
    @endif
    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirm-user-deletion">Eliminar cuenta</button>
    <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="delete-account-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h2 class="modal-title fs-5" id="delete-account-title">¿Eliminar tu cuenta?</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <p>Si tu cuenta tiene membresías registradas, no se podrá eliminar desde aquí. Ingresa tu contraseña para confirmar la eliminación.</p>
                        <label class="form-label" for="delete_password">Contraseña</label>
                        <input id="delete_password" name="password" type="password" class="form-control" required autocomplete="current-password">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-danger">Eliminar cuenta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
