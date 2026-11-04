<?php

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
    $plan = Plan::create(['name' => 'Plan Premium', 'price' => 149900, 'includes_all_disciplines' => true]);
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

it('saves and displays three disciplines for full sport', function () {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Full Sport', 'price' => 219900, 'discipline_limit' => 3]);
    $sede = Sede::create(['name' => 'Sede Norte']);

    $this->actingAs($user)->post(route('mi-membresia.store'), [
        'plan_id' => $plan->id, 'sede_id' => $sede->id, 'disciplines' => ['Fútbol', 'Natación', 'Gimnasio'],
    ])->assertRedirect(route('mi-membresia.index'));

    expect($user->memberships()->firstOrFail()->disciplines)->toBe(['Fútbol', 'Natación', 'Gimnasio']);
    $this->actingAs($user)->get(route('mi-membresia.index'))->assertOk()->assertSee('Fútbol, Natación, Gimnasio');
});

it('rejects the wrong number or repeated disciplines', function (array $disciplines, string $field) {
    $user = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan Full Sport', 'price' => 219900, 'discipline_limit' => 3]);
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
