<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

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
        ], [
            'photo.required' => 'Selecciona una foto de perfil.',
            'photo.image' => 'El archivo debe ser una imagen.',
            'photo.mimes' => 'La imagen debe estar en formato JPG, PNG o WebP.',
            'photo.max' => 'La imagen no puede superar los 2 MB.',
        ]);

        $path = $request->file('photo')->store('profile-photos', 's3');

        if ($path === false) {
            return back()->withErrors(['photo' => 'No se pudo guardar la foto. Inténtalo de nuevo.']);
        }

        try {
            $result = DB::transaction(function () use ($request, $path): array {
                $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
                if ($user->anonymized_at) {
                    return ['allowed' => false, 'previous' => null];
                }

                $previousPath = $user->profile_photo_path;
                $user->profile_photo_path = $path;
                $user->save();

                return ['allowed' => true, 'previous' => $previousPath];
            });
        } catch (Throwable $exception) {
            Storage::disk('s3')->delete($path);
            throw $exception;
        }

        if (! $result['allowed']) {
            Storage::disk('s3')->delete($path);
            abort(403);
        }

        $previousPath = $result['previous'];
        if ($previousPath && ! Storage::disk('s3')->delete($previousPath)) {
            Log::warning('No se pudo eliminar la foto de perfil anterior.', ['user_id' => $request->user()->id, 'path' => $previousPath]);
        }

        return back()->with('photo_status', 'Tu foto de perfil se guardó correctamente.');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $deleted = DB::transaction(function () use ($request): bool {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();
            abort_if($user->anonymized_at, 403);
            $path = $user->profile_photo_path;
            if ($path && ! Storage::disk('s3')->delete($path)) {
                return false;
            }

            $user->profile_photo_path = null;
            $user->save();

            return true;
        });

        if (! $deleted) {
            return back()->withErrors(['photo' => 'No se pudo eliminar la foto. Inténtalo de nuevo.']);
        }

        return back()->with('photo_status', 'Tu foto se quitó del perfil.');
    }
}
