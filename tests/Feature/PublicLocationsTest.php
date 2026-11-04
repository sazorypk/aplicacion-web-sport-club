<?php

use App\Models\Membership;
use App\Models\Plan;
use App\Models\Sede;
use App\Models\User;

it('shows active locations and example hours on the locations page', function () {
    $this->seed();
    Sede::create(['name' => 'Sede Centro', 'is_active' => false]);

    $this->get(route('sedes'))->assertOk()
        ->assertSee('Calle 52 # 48-35, Bello, Antioquia')
        ->assertSee('Carrera 43 # 38 Sur-25, Envigado, Antioquia')
        ->assertSee('Lunes a viernes')->assertSee('Sábados')->assertSee('Domingos')
        ->assertSee('Direcciones y horarios de ejemplo')
        ->assertDontSee('Sede Centro');
});

it('removes the centre location from enrollment while preserving existing memberships', function () {
    $user = User::factory()->create();
    $sede = Sede::create(['name' => 'Sede Centro', 'is_active' => true]);
    $plan = Plan::create(['name' => 'Plan de prueba', 'price' => 50000]);
    $membership = Membership::create(['user_id' => $user->id, 'sede_id' => $sede->id, 'plan_id' => $plan->id, 'price_at_signup' => 50000, 'status' => 'cancelled']);

    $migration = require database_path('migrations/2026_11_04_021628_update_sport_club_locations.php');
    $migration->up();

    $this->assertDatabaseHas('sedes', ['id' => $sede->id, 'is_active' => false]);
    $this->assertDatabaseHas('memberships', ['id' => $membership->id, 'sede_id' => $sede->id]);
    $this->actingAs($user)->get(route('mi-membresia.create'))->assertOk()->assertDontSee('Sede Centro');
});

it('opens the public sections as separate pages', function (string $route, string $heading) {
    $this->get(route($route))->assertOk()->assertSee($heading)->assertSee(route('sedes'));
})->with([
    ['disciplinas', 'ENCUENTRA TU DISCIPLINA'],
    ['planes', 'ELIGE TU PLAN IDEAL'],
    ['contacto', '¿LISTO PARA SUPERAR TUS LÍMITES?'],
]);

it('redirects the former about page to the home section', function () {
    $this->get(route('nosotros'))->assertRedirect('/#nosotros');
});

it('keeps the community and about sections on home while listing plans on their own page', function () {
    $this->get(route('inicio'))->assertOk()
        ->assertSee('MÁS QUE UN CLUB')
        ->assertSee('LO QUE DICE NUESTRA COMUNIDAD')
        ->assertDontSee('ELIGE TU PLAN IDEAL');
    $this->get(route('planes'))->assertOk()->assertSee('ELIGE TU PLAN IDEAL');
});

it('does not show the about link in the header', function () {
    $this->get(route('inicio'))->assertOk()
        ->assertDontSee('class="public-header__link">Nosotros</a>', false);
});
