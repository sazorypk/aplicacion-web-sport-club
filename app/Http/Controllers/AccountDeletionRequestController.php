<?php

namespace App\Http\Controllers;

use App\Models\AccountDeletionRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountDeletionRequestController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $request->validateWithBag('deletionRequest', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Ingresa tu contraseña para confirmar la solicitud.',
            'password.current_password' => 'La contraseña es incorrecta.',
        ]);

        $created = DB::transaction(function () use ($request): bool {
            $user = User::whereKey($request->user()->id)->lockForUpdate()->firstOrFail();

            if ($user->anonymized_at || AccountDeletionRequest::where('user_id', $user->id)->whereIn('status', ['pending', 'in_review'])->exists()) {
                return false;
            }

            AccountDeletionRequest::create(['user_id' => $user->id]);

            return true;
        });

        if (! $created) {
            return back()->withErrors(['request' => 'Ya tienes una solicitud pendiente o en revisión.'], 'deletionRequest');
        }

        return back()->with('deletion_request_status', 'Recibimos tu solicitud. El responsable revisará tu cuenta.');
    }
}
