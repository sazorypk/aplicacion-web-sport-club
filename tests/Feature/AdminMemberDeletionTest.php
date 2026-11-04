<?php

use App\Models\Membership;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sede;
use App\Models\User;

function membershipForDeletion(string $status): Membership
{
    $member = User::factory()->create();
    $plan = Plan::create(['name' => 'Plan de prueba', 'price' => 50000]);
    $sede = Sede::create(['name' => 'Sede de prueba']);

    return Membership::create([
        'user_id' => $member->id, 'plan_id' => $plan->id, 'sede_id' => $sede->id,
        'price_at_signup' => 50000, 'status' => $status,
    ]);
}

it('lets a master delete a cancelled membership and its payments before deleting the user', function () {
    $membership = membershipForDeletion('cancelled');
    $member = $membership->user;
    $payment = Payment::create(['membership_id' => $membership->id, 'amount' => 50000, 'status' => 'paid']);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->get(route('admin.miembros.index'))->assertOk()->assertSee('Eliminar membresía');
    $this->actingAs($master)->from(route('admin.miembros.index'))->delete(route('admin.membresias.destroy', $membership))
        ->assertRedirect(route('admin.miembros.index'))->assertSessionHas('success');

    $this->assertModelMissing($membership);
    $this->assertModelMissing($payment);
    $this->assertModelExists($member);
    $this->actingAs($master)->get(route('admin.miembros.index'))->assertSee('Eliminar usuario');
    $this->actingAs($master)->delete(route('admin.miembros.destroy', $member))->assertSessionHas('success');
    $this->assertModelMissing($member);
});

it('refuses to delete a pending or active membership and retains its payments', function (string $status) {
    $membership = membershipForDeletion($status);
    $payment = Payment::create(['membership_id' => $membership->id, 'amount' => 50000, 'status' => 'paid']);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->delete(route('admin.membresias.destroy', $membership))->assertSessionHas('error');

    $this->assertModelExists($membership);
    $this->assertModelExists($payment);
})->with(['pending', 'active']);

it('refuses to delete a user while any membership remains', function () {
    $membership = membershipForDeletion('cancelled');
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->delete(route('admin.miembros.destroy', $membership->user))->assertSessionHas('error');

    $this->assertModelExists($membership->user);
    $this->assertModelExists($membership);
});

it('never deletes a master account from the members panel', function () {
    $master = User::factory()->create(['role' => 'master']);
    $otherMaster = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->delete(route('admin.miembros.destroy', $otherMaster))->assertSessionHas('error');
    $this->actingAs($master)->delete(route('admin.miembros.destroy', $master))->assertSessionHas('error');

    $this->assertModelExists($master);
    $this->assertModelExists($otherMaster);
});

it('denies deletion to ordinary users and unauthenticated visitors', function () {
    $membership = membershipForDeletion('cancelled');
    $member = $membership->user;

    $this->delete(route('admin.membresias.destroy', $membership))->assertRedirect(route('login'));
    $this->delete(route('admin.miembros.destroy', $member))->assertRedirect(route('login'));
    $this->actingAs($member)->delete(route('admin.membresias.destroy', $membership))->assertForbidden();
    $this->actingAs($member)->delete(route('admin.miembros.destroy', $member))->assertForbidden();

    $this->assertModelExists($membership);
    $this->assertModelExists($member);
});

it('shows every membership and requires all of them to be deleted before deleting a user', function () {
    $membership = membershipForDeletion('cancelled');
    $member = $membership->user;
    $second = Membership::create([
        'user_id' => $member->id, 'plan_id' => $membership->plan_id, 'sede_id' => $membership->sede_id,
        'price_at_signup' => 50000, 'status' => 'cancelled',
    ]);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->get(route('admin.miembros.index'))
        ->assertSee(route('admin.membresias.destroy', $membership))
        ->assertSee(route('admin.membresias.destroy', $second));
    $this->actingAs($master)->delete(route('admin.membresias.destroy', $membership))->assertSessionHas('success');
    $this->actingAs($master)->delete(route('admin.miembros.destroy', $member))->assertSessionHas('error');

    $this->assertModelExists($member);
    $this->assertModelExists($second);
});
