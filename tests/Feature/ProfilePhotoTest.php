<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('a user can upload and view a private profile photo', function () {
    Storage::fake('s3');
    $user = User::factory()->create();
    $photo = UploadedFile::fake()->image('photo.jpg');

    $this->actingAs($user)->put(route('profile.photo.update'), ['photo' => $photo])
        ->assertRedirect()->assertSessionHasNoErrors();

    $path = $user->fresh()->profile_photo_path;
    Storage::disk('s3')->assertExists($path);
    $this->actingAs($user)->get(route('profile.photo.show'))->assertOk()->assertHeader('Cache-Control', 'no-store, private');
});

test('replacing and deleting a profile photo removes the previous file', function () {
    Storage::fake('s3');
    $user = User::factory()->create();
    Storage::disk('s3')->put('profile-photos/old.jpg', 'old');
    $user->forceFill(['profile_photo_path' => 'profile-photos/old.jpg'])->save();

    $this->actingAs($user)->put(route('profile.photo.update'), ['photo' => UploadedFile::fake()->image('new.jpg')])
        ->assertSessionHasNoErrors();
    $newPath = $user->fresh()->profile_photo_path;
    Storage::disk('s3')->assertMissing('profile-photos/old.jpg');
    Storage::disk('s3')->assertExists($newPath);

    $this->actingAs($user)->delete(route('profile.photo.destroy'))->assertSessionHasNoErrors();

    Storage::disk('s3')->assertMissing($newPath);
    expect($user->fresh()->profile_photo_path)->toBeNull();
});

test('invalid files cannot replace a profile photo', function () {
    Storage::fake('s3');
    $user = User::factory()->create();
    Storage::disk('s3')->put('profile-photos/old.jpg', 'old');
    $user->forceFill(['profile_photo_path' => 'profile-photos/old.jpg'])->save();

    $this->actingAs($user)->put(route('profile.photo.update'), ['photo' => UploadedFile::fake()->create('document.pdf', 100, 'application/pdf')])
        ->assertSessionHasErrors('photo');

    expect($user->fresh()->profile_photo_path)->toBe('profile-photos/old.jpg');
    Storage::disk('s3')->assertExists('profile-photos/old.jpg');
});

test('photos larger than two megabytes are rejected', function () {
    Storage::fake('s3');
    $user = User::factory()->create();

    $this->actingAs($user)->put(route('profile.photo.update'), ['photo' => UploadedFile::fake()->image('large.jpg')->size(2049)])
        ->assertSessionHasErrors('photo');

    expect($user->fresh()->profile_photo_path)->toBeNull();
    Storage::disk('s3')->assertDirectoryEmpty('profile-photos');
});

test('guests cannot access or change profile photos', function () {
    $this->get(route('profile.photo.show'))->assertRedirect(route('login'));
    $this->put(route('profile.photo.update'))->assertRedirect(route('login'));
    $this->delete(route('profile.photo.destroy'))->assertRedirect(route('login'));
});

test('a user cannot view another users profile photo', function () {
    Storage::fake('s3');
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    Storage::disk('s3')->put('profile-photos/private.jpg', 'private');
    $owner->forceFill(['profile_photo_path' => 'profile-photos/private.jpg'])->save();

    $this->actingAs($otherUser)->get(route('profile.photo.show'))->assertNotFound();
});

test('deleting an account removes its profile photo', function () {
    Storage::fake('s3');
    $user = User::factory()->create();
    Storage::disk('s3')->put('profile-photos/private.jpg', 'private');
    $user->forceFill(['profile_photo_path' => 'profile-photos/private.jpg'])->save();

    $this->actingAs($user)->delete(route('profile.destroy'), ['password' => 'password'])->assertRedirect('/');

    Storage::disk('s3')->assertMissing('profile-photos/private.jpg');
});
