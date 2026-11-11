<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'cedula' => ['required', 'string', 'max:30', 'unique:users,cedula'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'privacy_consent' => ['accepted'],
            'password' => ['required', 'confirmed',
                Rules\Password::defaults()],
        ], [
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'password.required' => 'La contraseña es obligatoria.',
            'privacy_consent.accepted' => 'Debes autorizar el tratamiento de datos para crear tu cuenta.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'cedula' => $request->cedula,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'privacy_consent_at' => now(),
            'privacy_policy_version' => User::PRIVACY_POLICY_VERSION,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('mi-membresia.create');
    }
}
