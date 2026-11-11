<section>
    <h2 class="h4">Información del perfil</h2>
    <p class="text-secondary">Actualiza tu nombre y correo electrónico.</p>
    @if (session('status') === 'profile-updated')
        <div class="alert alert-success" role="status">Tu perfil se actualizó correctamente.</div>
    @endif
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">@csrf</form>
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')
        <div class="mb-3">
            <label class="form-label" for="name">Nombre</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autocomplete="name">
            @error('name')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">Correo electrónico</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="email">
            @error('email')<div class="text-danger">{{ $message }}</div>@enderror
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <p class="text-secondary mt-2">Tu correo electrónico no está verificado. <button form="send-verification" type="submit" class="btn btn-link p-0">Reenviar correo de verificación</button></p>
                @if (session('status') === 'verification-link-sent')<p class="text-success">Se envió un nuevo enlace de verificación.</p>@endif
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Guardar cambios</button>
    </form>
</section>
