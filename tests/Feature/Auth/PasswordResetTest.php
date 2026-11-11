<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;

test('reset password link screen can be rendered', function () {
    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset password link can be requested', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset password screen can be rendered', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password can be reset with valid token', function () {
    Notification::fake();

    $user = User::factory()->create();

    $this->post('/forgot-password', ['email' => $user->email]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('login'))
            ->assertSessionHas('status', 'Tu contraseña se restableció correctamente.');

        return true;
    });
});

test('password reset email is in Spanish and links to the reset form', function () {
    $user = User::factory()->create(['email' => 'deportista@example.com']);
    $message = (new ResetPassword('token-de-prueba'))->toMail($user);

    expect($message->subject)->toBe('Recupera tu contraseña | Sport Club');
    expect($message->viewData['resetUrl'])->toBe(route('password.reset', [
        'token' => 'token-de-prueba',
        'email' => 'deportista@example.com',
    ]));
    expect((string) $message->render())
        ->toContain('Restablecer contraseña')
        ->toContain('Si no solicitaste cambiar tu contraseña')
        ->toContain('60 minutos');
});

test('password recovery messages are shown in Spanish', function () {
    Notification::fake();
    $user = User::factory()->create();

    $this->post(route('password.email'), ['email' => $user->email])
        ->assertSessionHas('status', 'Te enviamos un enlace para restablecer tu contraseña.');
    $this->post(route('password.email'), ['email' => 'desconocido@example.com'])
        ->assertSessionHasErrors(['email' => 'No encontramos una cuenta con ese correo electrónico.']);
});

test('invalid reset links report the error in Spanish', function () {
    $user = User::factory()->create();

    $this->post(route('password.store'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors(['email' => 'El enlace de recuperación no es válido o ha vencido.']);
});
