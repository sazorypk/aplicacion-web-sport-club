<?php

use App\Models\AccountDeletionRequest;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Sede;
use App\Models\User;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

test('the privacy policy is accessible to guests and signed in users', function () {
    $this->get(route('privacy.policy'))->assertOk()->assertSee('Brandon Ospina')->assertSee('privacidad@correo.sportclub.elnexusdigital.com');
    $this->actingAs(User::factory()->create())->get(route('privacy.policy'))->assertOk();
});

test('registration requires informed consent and does not create a user without it', function () {
    $this->post(route('register'), [
        'name' => 'Persona de prueba',
        'username' => 'persona-prueba',
        'cedula' => '12345678',
        'phone' => '3001234567',
        'email' => 'prueba@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertSessionHasErrors(['privacy_consent' => 'Debes autorizar el tratamiento de datos para crear tu cuenta.']);

    $this->assertGuest();
    $this->assertDatabaseMissing('users', ['email' => 'prueba@example.com']);
});

test('an authenticated user can request deletion without deleting their account', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->from(route('profile.edit'))
        ->post(route('profile.deletion-request.store'), ['password' => 'password'])
        ->assertRedirect(route('profile.edit'))->assertSessionHas('deletion_request_status');

    $this->assertDatabaseHas('account_deletion_requests', ['user_id' => $user->id, 'status' => 'pending']);
    $this->assertModelExists($user);
    $this->actingAs($user)->get(route('profile.edit'))->assertOk()->assertSee('pendiente de revisión');
});

test('deletion requests require the current password and cannot be duplicated', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->post(route('profile.deletion-request.store'), ['password' => 'incorrect'])
        ->assertSessionHasErrorsIn('deletionRequest', 'password');
    $this->assertDatabaseCount('account_deletion_requests', 0);

    $this->actingAs($user)->post(route('profile.deletion-request.store'), ['password' => 'password']);
    $this->actingAs($user)->post(route('profile.deletion-request.store'), ['password' => 'password'])
        ->assertSessionHasErrorsIn('deletionRequest', 'request');
    $this->assertDatabaseCount('account_deletion_requests', 1);
});

test('only a master can review requests and a review does not delete the user', function () {
    $deletionRequest = AccountDeletionRequest::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $member = User::factory()->create();

    $this->get(route('admin.privacy-requests.index'))->assertRedirect(route('login'));
    $this->actingAs($member)->get(route('admin.privacy-requests.index'))->assertForbidden();
    $this->actingAs($member)->patch(route('admin.privacy-requests.update', $deletionRequest))->assertForbidden();
    $this->actingAs($master)->get(route('admin.privacy-requests.index'))->assertOk()->assertSee($deletionRequest->user->email);
    $this->actingAs($master)->patch(route('admin.privacy-requests.update', $deletionRequest))->assertSessionHas('success');

    $this->assertDatabaseHas('account_deletion_requests', ['id' => $deletionRequest->id, 'status' => 'in_review']);
    $this->assertModelExists($deletionRequest->user);
});

test('a master can anonymize a reviewed account while retaining memberships and payments', function () {
    Storage::fake('s3');
    $member = User::factory()->create(['name' => 'Persona Original', 'username' => 'usuario_original', 'email' => 'original@example.com', 'cedula' => '12345678', 'phone' => '3001234567']);
    Storage::disk('s3')->put('profile-photos/photo.jpg', 'photo');
    $member->forceFill(['profile_photo_path' => 'profile-photos/photo.jpg', 'remember_token' => 'old-token'])->save();
    DB::table('password_reset_tokens')->insert(['email' => $member->email, 'token' => 'old-token']);
    $plan = Plan::create(['name' => 'Plan prueba', 'price' => 50000]);
    $sede = Sede::create(['name' => 'Sede prueba']);
    $membership = Membership::create(['user_id' => $member->id, 'plan_id' => $plan->id, 'sede_id' => $sede->id, 'price_at_signup' => 50000, 'status' => 'active']);
    $payment = Payment::create(['membership_id' => $membership->id, 'amount' => 50000, 'status' => 'paid']);
    $deletionRequest = AccountDeletionRequest::factory()->create(['user_id' => $member->id, 'status' => 'in_review']);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password', 'confirm_anonymization' => '1'])
        ->assertSessionHas('success');

    $member->refresh();
    expect($member->name)->toBe('Cuenta anonimizada')
        ->and($member->username)->not->toBe('usuario_original')
        ->and($member->email)->not->toBe('original@example.com')
        ->and($member->cedula)->toBeNull()
        ->and($member->phone)->toBeNull()
        ->and($member->profile_photo_path)->toBeNull()
        ->and($member->remember_token)->toBeNull()
        ->and($member->anonymized_at)->not->toBeNull()
        ->and(Hash::check('password', $member->password))->toBeFalse();
    Storage::disk('s3')->assertMissing('profile-photos/photo.jpg');
    $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'original@example.com']);
    $this->assertDatabaseHas('memberships', ['id' => $membership->id, 'user_id' => $member->id, 'status' => 'cancelled']);
    $this->assertDatabaseHas('payments', ['id' => $payment->id, 'membership_id' => $membership->id]);
    $this->assertDatabaseHas('account_deletion_requests', ['id' => $deletionRequest->id, 'status' => 'completed']);
    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password', 'confirm_anonymization' => '1'])
        ->assertSessionHas('error');
    $this->assertDatabaseHas('payments', ['id' => $payment->id, 'membership_id' => $membership->id]);
    $this->actingAs($member)->get(route('profile.edit'))->assertRedirect(route('login'));
    $this->assertGuest();
    $this->post(route('login'), ['username' => 'usuario_original', 'password' => 'password'])->assertSessionHasErrors('username');
});

test('anonymization requires a master, review, password and confirmation', function () {
    $deletionRequest = AccountDeletionRequest::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $member = User::factory()->create();

    $this->post(route('admin.privacy-requests.anonymize', $deletionRequest))->assertRedirect(route('login'));
    $this->actingAs($member)->post(route('admin.privacy-requests.anonymize', $deletionRequest))->assertForbidden();
    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'wrong', 'confirm_anonymization' => '1'])
        ->assertSessionHasErrorsIn('anonymization', 'password');
    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password'])
        ->assertSessionHasErrorsIn('anonymization', 'confirm_anonymization');
    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password', 'confirm_anonymization' => '1'])
        ->assertSessionHas('error');

    expect($deletionRequest->user->fresh()->anonymized_at)->toBeNull();
    $this->assertDatabaseHas('account_deletion_requests', ['id' => $deletionRequest->id, 'status' => 'pending']);
});

test('reviewed requests expose the confirmation form only to the master', function () {
    $deletionRequest = AccountDeletionRequest::factory()->create(['status' => 'in_review']);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->get(route('admin.privacy-requests.index'))
        ->assertOk()->assertSee('Anonimizar cuenta')->assertSee('Confirmo la anonimización irreversible');
});

test('master accounts cannot be anonymized through a deletion request', function () {
    $target = User::factory()->create(['role' => 'master']);
    $deletionRequest = AccountDeletionRequest::factory()->create(['user_id' => $target->id, 'status' => 'in_review']);
    $master = User::factory()->create(['role' => 'master']);

    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password', 'confirm_anonymization' => '1'])
        ->assertSessionHas('error');

    expect($target->fresh()->anonymized_at)->toBeNull();
});

test('a failed photo removal prevents account anonymization', function () {
    $member = User::factory()->create();
    $member->forceFill(['profile_photo_path' => 'profile-photos/photo.jpg'])->save();
    $deletionRequest = AccountDeletionRequest::factory()->create(['user_id' => $member->id, 'status' => 'in_review']);
    $master = User::factory()->create(['role' => 'master']);
    $disk = Mockery::mock(FilesystemAdapter::class);
    $disk->shouldReceive('delete')->once()->with('profile-photos/photo.jpg')->andReturnFalse();
    Storage::shouldReceive('disk')->once()->with('s3')->andReturn($disk);

    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password', 'confirm_anonymization' => '1'])
        ->assertSessionHas('error');

    expect($member->fresh()->anonymized_at)->toBeNull()
        ->and($member->fresh()->profile_photo_path)->toBe('profile-photos/photo.jpg');
    $this->assertDatabaseHas('account_deletion_requests', ['id' => $deletionRequest->id, 'status' => 'in_review']);
});

test('processing a request removes database sessions for the anonymized account', function () {
    config(['session.driver' => 'database']);
    $member = User::factory()->create();
    $master = User::factory()->create(['role' => 'master']);
    $deletionRequest = AccountDeletionRequest::factory()->create(['user_id' => $member->id, 'status' => 'in_review']);
    DB::table('sessions')->insert([
        'id' => 'member-session',
        'user_id' => $member->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'Test browser',
        'payload' => base64_encode('{}'),
        'last_activity' => now()->timestamp,
    ]);

    $this->actingAs($master)->post(route('admin.privacy-requests.anonymize', $deletionRequest), ['password' => 'password', 'confirm_anonymization' => '1'])
        ->assertSessionHas('success');

    $this->assertDatabaseMissing('sessions', ['id' => 'member-session']);
});
