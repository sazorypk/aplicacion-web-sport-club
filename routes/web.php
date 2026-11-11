<?php

use App\Http\Controllers\AccountDeletionRequestController;
use App\Http\Controllers\Admin\AccountDeletionRequestController as AdminAccountDeletionRequestController;
use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilePhotoController;
use App\Models\Plan;
use App\Models\Sede;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function (Request $request) {
    if ($request->user()) {
        return redirect()->route($request->user()->role === 'master' ? 'admin.dashboard' : 'dashboard');
    }

    return view('pages.inicio');
})->name('inicio');
Route::view('/privacidad', 'pages.privacidad')->name('privacy.policy');
Route::middleware('guest')->group(function () {
    Route::redirect('/nosotros', '/#nosotros')->name('nosotros');
    Route::view('/disciplinas', 'pages.disciplinas')->name('disciplinas');
    Route::get('/planes', fn () => view('pages.planes', ['plans' => Plan::where('is_active', true)->orderBy('price')->get()]))->name('planes');
    Route::get('/sedes', fn () => view('pages.sedes', ['sedes' => Sede::where('is_active', true)->orderBy('name')->get()]))->name('sedes');
    Route::view('/contacto', 'pages.contacto')->name('contacto');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile/photo', [ProfilePhotoController::class, 'show'])->name('profile.photo.show');
    Route::put('/profile/photo', [ProfilePhotoController::class, 'update'])->name('profile.photo.update');
    Route::delete('/profile/photo', [ProfilePhotoController::class, 'destroy'])->name('profile.photo.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/deletion-request', [AccountDeletionRequestController::class, 'store'])->name('profile.deletion-request.store');
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/mi-membresia', [MembershipController::class, 'index'])->name('mi-membresia.index');
    Route::get('/mi-membresia/crear', [MembershipController::class, 'create'])->name('mi-membresia.create');
    Route::post('/mi-membresia', [MembershipController::class, 'store'])->name('mi-membresia.store');
    Route::patch('/mi-membresia/{membership}/disciplinas', [MembershipController::class, 'updateDisciplines'])->name('mi-membresia.disciplinas');
    Route::post('/mi-membresia/{membership}/pago-simulado', [MembershipController::class, 'simulatePayment'])->name('mi-membresia.pago-simulado');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'master'])->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::get('/solicitudes-privacidad', [AdminAccountDeletionRequestController::class, 'index'])->name('privacy-requests.index');
    Route::patch('/solicitudes-privacidad/{accountDeletionRequest}', [AdminAccountDeletionRequestController::class, 'update'])->name('privacy-requests.update');
    Route::post('/solicitudes-privacidad/{accountDeletionRequest}/anonimizar', [AdminAccountDeletionRequestController::class, 'anonymize'])->name('privacy-requests.anonymize');
    Route::resource('planes', PlanController::class)->except('show');
    Route::get('/miembros', [MemberController::class, 'index'])->name('miembros.index');
    Route::patch('/membresias/{membership}/cancelar', [MemberController::class, 'cancelMembership'])->name('membresias.cancelar');
    Route::delete('/membresias/{membership}', [MemberController::class, 'destroyMembership'])->name('membresias.destroy');
    Route::delete('/miembros/{member}', [MemberController::class, 'destroy'])->name('miembros.destroy');
});

require __DIR__.'/auth.php';
