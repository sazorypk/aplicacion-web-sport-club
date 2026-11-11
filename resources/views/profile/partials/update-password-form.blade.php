<section>
    <h2 class="h4">Cambiar contraseña</h2>
    <p class="text-secondary">Usa una contraseña larga y difícil de adivinar para proteger tu cuenta.</p>
    @if (session('status') === 'password-updated')
        <div class="alert alert-success" role="status">Tu contraseña se actualizó correctamente.</div>
    @endif
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label" for="update_password_current_password">Contraseña actual</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" required autocomplete="current-password">
            @if ($errors->updatePassword->has('current_password'))<div class="text-danger">{{ $errors->updatePassword->first('current_password') }}</div>@endif
        </div>
        <div class="mb-3">
            <label class="form-label" for="update_password_password">Nueva contraseña</label>
            <input id="update_password_password" name="password" type="password" class="form-control" required autocomplete="new-password">
            @if ($errors->updatePassword->has('password'))<div class="text-danger">{{ $errors->updatePassword->first('password') }}</div>@endif
        </div>
        <div class="mb-3">
            <label class="form-label" for="update_password_password_confirmation">Confirmar contraseña</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" required autocomplete="new-password">
        </div>
        <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
    </form>
</section>
