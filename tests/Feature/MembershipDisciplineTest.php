<?php

use App\Models\Membership;
use App\Models\Plan;
use App\Models\Sede;
use App\Models\User;

it('saves and displays the selected discipline for a single-discipline plan', function (string $name) {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => $name, 'price' => 89000]);
    $sede = Sede::create(['name' => 'Sede Norte']);

    $this->actingAs($user)->post(route('mi-membresia.store'), [
        'plan_id' => $plan->id, 'sede_id' => $sede->id, 'disciplines' => ['Natación'],
    ])->assertRedirect(route('mi-membresia.index'));

    expect($user->memberships()->firstOrFail()->disciplines)->toBe(['Natación']);
    $this->actingAs($user)->get(route('mi-membresia.index'))->assertOk()->assertSee('Disciplinas: Natación');
})->with(['Plan Básico']);

it('requires an available discipline for single-discipline plans', function (?array $disciplines, string $field, string $error) {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Básico', 'price' => 89000]);
    $sede = Sede::create(['name' => 'Sede Norte']);

    $this->actingAs($user)->post(route('mi-membresia.store'), [
        'plan_id' => $plan->id, 'sede_id' => $sede->id, 'disciplines' => $disciplines,
    ])->assertSessionHasErrors([$field => $error]);

    $this->assertDatabaseCount('memberships', 0);
})->with([
    'missing' => [null, 'disciplines', 'Selecciona las disciplinas que deseas practicar.'],
    'invalid' => [['Tenis'], 'disciplines.0', 'Selecciona una disciplina válida.'],
]);

it('enrolls in an all-discipline plan without selecting a discipline', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Full Sport', 'price' => 219900, 'includes_all_disciplines' => true]);
    $sede = Sede::create(['name' => 'Sede Norte']);

    $this->actingAs($user)->get(route('mi-membresia.create'))->assertOk()
        ->assertSee('Este plan incluye todas las disciplinas.')
        ->assertSee('data-all-disciplines="1"', false);

    $this->actingAs($user)->post(route('mi-membresia.store'), [
        'plan_id' => $plan->id, 'sede_id' => $sede->id, 'disciplines' => ['Fútbol'],
    ])->assertRedirect(route('mi-membresia.index'));

    $this->assertDatabaseHas('memberships', ['user_id' => $user->id, 'plan_id' => $plan->id, 'discipline' => null]);
    $this->actingAs($user)->get(route('mi-membresia.index'))->assertOk()->assertSee('Incluye todas las disciplinas.');
});

it('allows a master to configure whether a plan includes every discipline', function () {
    $master = User::factory()->create(['role' => 'master']);
    $plan = Plan::create(['name' => 'Plan editable', 'price' => 50000]);

    $this->actingAs($master)->put(route('admin.planes.update', $plan), [
        'name' => $plan->name, 'price' => 50000, 'is_active' => 1, 'includes_all_disciplines' => 1,
    ])->assertRedirect(route('admin.planes.index'));

    $this->assertDatabaseHas('plans', ['id' => $plan->id, 'includes_all_disciplines' => true]);
});

it('saves and displays three disciplines for premium', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Premium', 'price' => 149900, 'discipline_limit' => 3]);
    $sede = Sede::create(['name' => 'Sede Norte']);

    $this->actingAs($user)->post(route('mi-membresia.store'), [
        'plan_id' => $plan->id, 'sede_id' => $sede->id, 'disciplines' => ['Fútbol', 'Natación', 'Gimnasio'],
    ])->assertRedirect(route('mi-membresia.index'));

    expect($user->memberships()->firstOrFail()->disciplines)->toBe(['Fútbol', 'Natación', 'Gimnasio']);
    $this->actingAs($user)->get(route('mi-membresia.index'))->assertOk()->assertSee('Fútbol, Natación, Gimnasio');
});

it('rejects the wrong number or repeated disciplines', function (array $disciplines, string $field) {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Premium', 'price' => 149900, 'discipline_limit' => 3]);
    $sede = Sede::create(['name' => 'Sede Norte']);

    $this->actingAs($user)->post(route('mi-membresia.store'), [
        'plan_id' => $plan->id, 'sede_id' => $sede->id, 'disciplines' => $disciplines,
    ])->assertSessionHasErrors($field);
    $this->assertDatabaseCount('memberships', 0);
})->with([
    'too few' => [['Fútbol'], 'disciplines'],
    'too many' => [['Fútbol', 'Natación', 'Gimnasio', 'Atletismo'], 'disciplines'],
    'repeated' => [['Fútbol', 'Natación', 'Fútbol'], 'disciplines.0'],
]);

it('seeds premium with three disciplines and full sport with all disciplines', function () {
    $this->seed();

    $this->assertDatabaseHas('plans', ['name' => 'Plan Premium', 'discipline_limit' => 3, 'includes_all_disciplines' => false]);
    $this->assertDatabaseHas('plans', ['name' => 'Plan Full Sport', 'includes_all_disciplines' => true]);
    $this->get(route('planes'))->assertOk()
        ->assertSee('Acceso a tres disciplinas y zonas de entrenamiento.')
        ->assertSee('Acceso a todas las disciplinas e instalaciones del club.');
});

it('updates existing plan benefits without changing memberships', function () {
    $premium = Plan::create(['name' => 'Plan Premium', 'price' => 149900, 'includes_all_disciplines' => true]);
    $full = Plan::create(['name' => 'Plan Full Sport', 'price' => 219900, 'discipline_limit' => 3]);
    $user = User::factory()->create();
    $sede = Sede::create(['name' => 'Sede Norte']);
    $membership = Membership::create(['user_id' => $user->id, 'plan_id' => $premium->id, 'sede_id' => $sede->id, 'price_at_signup' => 149900, 'status' => 'active']);

    $migration = require database_path('migrations/2026_11_04_032903_correct_premium_and_full_sport_benefits.php');
    $migration->up();

    $this->assertDatabaseHas('plans', ['id' => $premium->id, 'discipline_limit' => 3, 'includes_all_disciplines' => false]);
    $this->assertDatabaseHas('plans', ['id' => $full->id, 'includes_all_disciplines' => true]);
    $this->assertModelExists($membership);
    $this->actingAs($user)->get(route('mi-membresia.index'))->assertOk()->assertSee('Disciplinas pendientes de selección');
});

it('shows all disciplines for a full sport membership with earlier selections', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Full Sport', 'price' => 219900, 'includes_all_disciplines' => true]);
    $sede = Sede::create(['name' => 'Sede Norte']);
    Membership::create(['user_id' => $user->id, 'plan_id' => $plan->id, 'sede_id' => $sede->id, 'price_at_signup' => 219900, 'status' => 'active', 'disciplines' => ['Fútbol', 'Natación', 'Gimnasio']]);

    $this->actingAs($user)->get(route('mi-membresia.index'))->assertOk()->assertSee('Incluye todas las disciplinas.');
});

it('lets a premium member complete their previously missing discipline selections once', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Premium', 'price' => 149900, 'discipline_limit' => 3]);
    $sede = Sede::create(['name' => 'Sede Norte']);
    $membership = Membership::create(['user_id' => $user->id, 'plan_id' => $plan->id, 'sede_id' => $sede->id, 'price_at_signup' => 149900, 'status' => 'active']);

    $this->actingAs($user)->get(route('mi-membresia.index'))->assertSee('Completa tu selección de disciplinas');
    $this->actingAs($user)->patch(route('mi-membresia.disciplinas', $membership), ['disciplines' => ['Fútbol']])->assertSessionHasErrors('disciplines');
    $this->actingAs($user)->patch(route('mi-membresia.disciplinas', $membership), ['disciplines' => ['Fútbol', 'Natación', 'Gimnasio']])->assertSessionHas('success');
    expect($membership->fresh()->disciplines)->toBe(['Fútbol', 'Natación', 'Gimnasio']);
    $this->actingAs($user)->patch(route('mi-membresia.disciplinas', $membership), ['disciplines' => ['Fútbol', 'Atletismo', 'Gimnasio']])->assertSessionHas('error');
    expect($membership->fresh()->disciplines)->toBe(['Fútbol', 'Natación', 'Gimnasio']);
});

it('prevents another user from selecting disciplines for a membership', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Premium', 'price' => 149900, 'discipline_limit' => 3]);
    $sede = Sede::create(['name' => 'Sede Norte']);
    $membership = Membership::create(['user_id' => $owner->id, 'plan_id' => $plan->id, 'sede_id' => $sede->id, 'price_at_signup' => 149900, 'status' => 'active']);

    $this->actingAs($other)->patch(route('mi-membresia.disciplinas', $membership), ['disciplines' => ['Fútbol', 'Natación', 'Gimnasio']])->assertForbidden();
    expect($membership->fresh()->disciplines)->toBeNull();
});
