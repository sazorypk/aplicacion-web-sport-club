<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfilePhotoController extends Controller
{
    public function show(Request $request): StreamedResponse
    {
        $path = $request->user()->profile_photo_path;
        abort_unless($path && Storage::disk('s3')->exists($path), 404);

        return Storage::disk('s3')->response($path, null, [
            'Cache-Control' => 'private, no-store',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = $request->user();
        $previousPath = $user->profile_photo_path;
        $path = $request->file('photo')->store('profile-photos', 's3');

        if ($path === false) {
            return back()->withErrors(['photo' => 'No se pudo guardar la foto. Inténtalo de nuevo.']);
        }

        $user->profile_photo_path = $path;
        $user->save();

        if ($previousPath && ! Storage::disk('s3')->delete($previousPath)) {
            Log::warning('No se pudo eliminar la foto de perfil anterior.', ['user_id' => $user->id, 'path' => $previousPath]);
        }

        return back()->with('photo_status', 'Tu foto de perfil se guardó correctamente.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();
        $path = $user->profile_photo_path;

        if ($path && ! Storage::disk('s3')->delete($path)) {
            return back()->withErrors(['photo' => 'No se pudo eliminar la foto. Inténtalo de nuevo.']);
        }

        $user->profile_photo_path = null;
        $user->save();

        return back()->with('photo_status', 'Tu foto se quitó del perfil.');
    }
}
