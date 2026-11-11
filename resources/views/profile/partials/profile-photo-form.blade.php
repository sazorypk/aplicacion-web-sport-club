<section>
    <h2 class="h4">Foto de perfil</h2>
    <p class="text-secondary">Elige una imagen JPG, PNG o WebP de hasta 2 MB.</p>
    @if ($user->profile_photo_path)
        <img src="{{ route('profile.photo.show') }}" alt="Tu foto de perfil" class="rounded-circle object-fit-cover mb-3" width="96" height="96">
    @endif
    @if (session('photo_status'))
        <div class="alert alert-success" role="status">{{ session('photo_status') }}</div>
    @endif
    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label" for="photo">Selecciona tu foto</label>
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" required class="form-control">
            @error('photo')<div class="text-danger">{{ $message }}</div>@enderror
        </div>
        <button type="submit" class="btn btn-primary">Guardar foto</button>
    </form>
    @if ($user->profile_photo_path)
        <form method="POST" action="{{ route('profile.photo.destroy') }}" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Quitar foto</button>
        </form>
    @endif
</section>
