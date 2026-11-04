<?php

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'cedula' => '12345678',
        'phone' => '3001234567',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('mi-membresia.create'));
    $this->assertDatabaseHas('users', ['username' => 'testuser', 'email' => 'test@example.com', 'role' => 'member']);
});

test('registration requires a unique username', function () {
    User::factory()->create(['username' => 'taken']);

    $this->post('/register', [
        'name' => 'Another User',
        'username' => 'taken',
        'cedula' => '12345678',
        'phone' => '3001234567',
        'email' => 'another@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors('username');

    $this->assertGuest();
});

test('registration shows the minimum password length in Spanish', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'cedula' => '12345678',
        'phone' => '3001234567',
        'email' => 'test@example.com',
        'password' => '123',
        'password_confirmation' => '123',
    ])->assertSessionHasErrors(['password' => 'La contraseña debe tener al menos 8 caracteres.']);

    $this->assertGuest();
});
