<?php

use App\Models\Membership;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sede;
use App\Models\User;

function membershipForCancellation(string $status): Membership
{
    $member = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan de prueba', 'price' => 50000]);
    $sede = Sede::create(['name' => 'Sede de prueba']);

    return Membership::create([
        'user_id' => $member->id,
        'plan_id' => $plan->id,
        'sede_id' => $sede->id,
        'price_at_signup' => 50000,
        'status' => $status,
    ]);
}

test('a master can cancel an active or pending membership and keep its payments', function (string $status) {
    $membership = membershipForCancellation($status);
    $payment = Payment::create(['membership_id' => $membership->id, 'amount' => 50000, 'status' => 'paid']);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->get(route('admin.miembros.index'))
        ->assertOk()->assertSee('Cancelar membresía');

    $this->from(route('admin.miembros.index'))->actingAs($master)
        ->patch(route('admin.membresias.cancelar', $membership))
        ->assertRedirect(route('admin.miembros.index'))->assertSessionHas('success');

    $this->assertDatabaseHas('memberships', ['id' => $membership->id, 'status' => 'cancelled']);
    $this->assertDatabaseHas('payments', ['id' => $payment->id, 'membership_id' => $membership->id]);

    $this->actingAs($membership->user)->get(route('mi-membresia.index'))
        ->assertOk()->assertSee('Cancelada')->assertSee('Elegir plan');
})->with(['pending', 'active']);

test('a member cannot cancel a membership through the master route', function () {
    $membership = membershipForCancellation('active');

    $this->actingAs($membership->user)->patch(route('admin.membresias.cancelar', $membership))->assertForbidden();

    $this->assertDatabaseHas('memberships', ['id' => $membership->id, 'status' => 'active']);
});

test('a guest is redirected to login when trying to cancel a membership', function () {
    $membership = membershipForCancellation('pending');

    $this->patch(route('admin.membresias.cancelar', $membership))->assertRedirect(route('login'));

    $this->assertDatabaseHas('memberships', ['id' => $membership->id, 'status' => 'pending']);
});

test('a cancelled membership cannot be cancelled again or activated by simulated payment', function () {
    $membership = membershipForCancellation('cancelled');
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->from(route('admin.miembros.index'))
        ->patch(route('admin.membresias.cancelar', $membership))->assertSessionHas('error');

    $this->actingAs($membership->user)->post(route('mi-membresia.pago-simulado', $membership))
        ->assertSessionHas('error');

    $this->assertDatabaseHas('memberships', ['id' => $membership->id, 'status' => 'cancelled']);
    $this->assertDatabaseCount('payments', 0);
});
