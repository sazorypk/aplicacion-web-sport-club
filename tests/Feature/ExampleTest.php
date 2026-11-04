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
        ->assertRedirect(route('dashboard'));

    $this->actingAs($user)->get(route('dashboard'))
        ->assertOk()
        ->assertDontSee('id="landing-menu"', false)
        ->assertDontSee('UN SOLO CLUB E INFINITAS FORMAS DE SUPERARTE.')
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
        ->assertRedirect(route('admin.dashboard'));

    $this->actingAs($user)->get(route('admin.dashboard'))
        ->assertOk()
        ->assertDontSee('id="landing-menu"', false)
        ->assertDontSee('UN SOLO CLUB E INFINITAS FORMAS DE SUPERARTE.')
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

it('shows the same public header on the landing page and login screen', function () {
    foreach ([route('inicio'), route('login')] as $url) {
        $this->get($url)
            ->assertOk()
            ->assertSee('class="public-header__nav"', false)
            ->assertSee('id="landing-menu"', false)
            ->assertSee(route('disciplinas'))
            ->assertSee('Únete ahora');
    }
});

it('shows all disciplines included in the seeded premium plan', function () {
    $this->seed();

    $this->get(route('inicio'))
        ->assertOk()
        ->assertSee('Acceso a todas las disciplinas y zonas de entrenamiento.');
});

it('updates the existing default premium description without changing other plans', function () {
    Plan::create(['name' => 'Plan Premium', 'description' => 'Acceso ampliado a clases y zonas de entrenamiento.', 'price' => 149900, 'is_active' => true]);
    Plan::create(['name' => 'Otro plan', 'description' => 'Acceso ampliado a clases y zonas de entrenamiento.', 'price' => 89000, 'is_active' => true]);

    $migration = require database_path('migrations/2026_11_04_020427_update_premium_plan_description.php');
    $migration->up();

    $this->assertDatabaseHas('plans', ['name' => 'Plan Premium', 'description' => 'Acceso a tres disciplinas y zonas de entrenamiento.']);
    $this->assertDatabaseHas('plans', ['name' => 'Otro plan', 'description' => 'Acceso ampliado a clases y zonas de entrenamiento.']);
});

it('preserves a customized premium description during the data migration', function () {
    Plan::create(['name' => 'Plan Premium', 'description' => 'Descripción personalizada', 'price' => 149900, 'is_active' => true]);

    $migration = require database_path('migrations/2026_11_04_020427_update_premium_plan_description.php');
    $migration->up();

    $this->assertDatabaseHas('plans', ['name' => 'Plan Premium', 'description' => 'Descripción personalizada']);
});
