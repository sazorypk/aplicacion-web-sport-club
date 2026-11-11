<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Foto de perfil</h2>
        <p class="mt-1 text-sm text-gray-600">Elige una imagen JPG, PNG o WebP de hasta 2 MB.</p>
    </header>

    @if ($user->profile_photo_path)
        <img src="{{ route('profile.photo.show') }}" alt="Tu foto de perfil" class="mt-4 h-24 w-24 rounded-full object-cover">
    @endif

    @if (session('photo_status'))
        <p class="mt-4 text-sm text-green-700" role="status">{{ session('photo_status') }}</p>
    @endif

    <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
        @csrf
        @method('PUT')
        <div>
            <x-input-label for="photo" value="Selecciona tu foto" />
            <input id="photo" name="photo" type="file" accept="image/jpeg,image/png,image/webp" required class="mt-1 block w-full text-sm text-gray-700">
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>
        <x-primary-button>Guardar foto</x-primary-button>
    </form>

    @if ($user->profile_photo_path)
        <form method="POST" action="{{ route('profile.photo.destroy') }}" class="mt-4">
            @csrf
            @method('DELETE')
            <x-danger-button>Eliminar foto</x-danger-button>
        </form>
    @endif
</section>
