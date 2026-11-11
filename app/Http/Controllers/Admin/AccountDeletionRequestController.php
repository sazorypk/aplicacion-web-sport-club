<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountDeletionRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class AccountDeletionRequestController extends Controller
{
    public function index(): View
    {
        $requests = AccountDeletionRequest::with('user')->latest()->paginate(15);

        return view('admin.privacy-requests', compact('requests'));
    }

    public function update(AccountDeletionRequest $accountDeletionRequest): RedirectResponse
    {
        $updated = AccountDeletionRequest::whereKey($accountDeletionRequest->id)
            ->where('status', 'pending')
            ->update(['status' => 'in_review', 'reviewed_at' => now()]);

        return back()->with($updated ? 'success' : 'error', $updated ? 'Solicitud marcada en revisión.' : 'La solicitud ya no está pendiente.');
    }

    public function anonymize(Request $request, AccountDeletionRequest $accountDeletionRequest): RedirectResponse
    {
        $request->validateWithBag('anonymization', [
            'password' => ['required', 'current_password'],
            'confirm_anonymization' => ['accepted'],
        ], [
            'password.required' => 'Ingresa tu contraseña para confirmar.',
            'password.current_password' => 'La contraseña es incorrecta.',
            'confirm_anonymization.accepted' => 'Confirma que deseas anonimizar la cuenta.',
        ]);

        $result = DB::transaction(function () use ($accountDeletionRequest): string {
            $user = User::whereKey($accountDeletionRequest->user_id)->lockForUpdate()->first();
            $deletionRequest = AccountDeletionRequest::whereKey($accountDeletionRequest->id)->lockForUpdate()->firstOrFail();

            if (! $user || $deletionRequest->user_id !== $user->id || $user->role === 'master' || $user->anonymized_at || $deletionRequest->status !== 'in_review') {
                return 'invalid';
            }

            if ($user->profile_photo_path) {
                try {
                    if (! Storage::disk('s3')->delete($user->profile_photo_path)) {
                        return 'photo_error';
                    }
                } catch (Throwable $exception) {
                    report($exception);

                    return 'photo_error';
                }
            }

            $resetTable = config('auth.passwords.'.config('auth.defaults.passwords').'.table');
            DB::table($resetTable)->where('email', $user->email)->delete();

            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))->table(config('session.table'))->where('user_id', $user->id)->delete();
            }

            $user->memberships()->whereIn('status', ['active', 'pending'])->update(['status' => 'cancelled']);
            $identifier = (string) Str::uuid();
            $user->forceFill([
                'name' => 'Cuenta anonimizada',
                'username' => 'anon_'.$identifier,
                'email' => 'anon_'.$identifier.'@example.invalid',
                'cedula' => null,
                'phone' => null,
                'profile_photo_path' => null,
                'email_verified_at' => null,
                'privacy_consent_at' => null,
                'privacy_policy_version' => null,
                'password' => Hash::make(Str::random(64)),
                'remember_token' => null,
                'anonymized_at' => now(),
            ])->save();

            $deletionRequest->update(['status' => 'completed', 'completed_at' => now()]);

            return 'completed';
        });

        return match ($result) {
            'completed' => back()->with('success', 'La cuenta se anonimizó y se conservaron las membresías y pagos simulados.'),
            'photo_error' => back()->with('error', 'No se pudo quitar la foto. La cuenta no se modificó; inténtalo de nuevo.'),
            default => back()->with('error', 'La solicitud no se puede procesar o pertenece a una cuenta maestra.'),
        };
    }
}
