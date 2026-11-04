<?php

use App\Models\Plan;
use App\Models\User;

it('returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('shows the landing navigation and account links to guests', function () {
    $this->get(route('inicio'))
        ->assertOk()
        ->assertSee('id="landing-menu"', false)
        ->assertSee('Iniciar sesión')
        ->assertSee('Únete ahora');
});

it('shows account navigation instead of the public menu for signed in users', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get(route('inicio'))
        ->assertOk()
        ->assertDontSee('id="landing-menu"', false)
        ->assertSee('Mi panel')
        ->assertSee('Cerrar sesión');

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('navbar-nav me-auto', false)
        ->assertSee('Mi panel');
});

it('shows the master panel link to signed in administrators', function () {
    $user = User::factory()->create(['role' => 'master']);

    $this->actingAs($user)->get(route('inicio'))
        ->assertOk()
        ->assertDontSee('id="landing-menu"', false)
        ->assertSee('Panel maestro');
});

it('displays only active plans with their current prices on the landing page', function () {
    Plan::create(['name' => 'Plan vigente', 'description' => 'Entrenamiento', 'price' => 125000, 'is_active' => true]);
    Plan::create(['name' => 'Plan inactivo', 'description' => 'Anterior', 'price' => 50000, 'is_active' => false]);

    $this->get(route('inicio'))
        ->assertOk()
        ->assertSee('Plan vigente')
        ->assertSee('$125.000')
        ->assertDontSee('Plan inactivo');
});
