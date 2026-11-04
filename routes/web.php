<?php

use App\Http\Controllers\Admin\MemberController;
use App\Http\Controllers\Admin\PlanController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\ProfileController;
use App\Models\Plan;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.inicio')->name('inicio');
Route::view('/nosotros', 'pages.nosotros')->name('nosotros');
Route::get('/disciplinas', function () {
    $disciplinas = [
        ['nombre' => "F\u{00FA}tbol", 'descripcion' => 'Mejora tu tecnica, resistencia y trabajo en equipo.', 'imagen' => 'futbol.webp'],
        ['nombre' => "Nataci\u{00F3}n", 'descripcion' => 'Entrenamiento completo para todos los niveles.', 'imagen' => 'natacion.webp'],
        ['nombre' => 'Baloncesto', 'descripcion' => 'Practica velocidad, coordinacion y estrategia.', 'imagen' => 'baloncesto.webp'],
        ['nombre' => 'Voleibol', 'descripcion' => 'Desarrolla agilidad y juego en equipo.', 'imagen' => 'voleibol.webp'],
        ['nombre' => 'Atletismo', 'descripcion' => 'Entrena fuerza, velocidad y resistencia.', 'imagen' => 'atletismo.webp'],
        ['nombre' => 'Gimnasio', 'descripcion' => 'Trabaja fuerza y acondicionamiento fisico.', 'imagen' => 'gimnasio.webp'],
    ];

    return view('pages.disciplinas', compact('disciplinas'));
})->name('disciplinas');
Route::get('/planes', fn () => view('pages.planes', ['plans' => Plan::where('is_active', true)->orderBy('price')->get()]))->name('planes');
Route::view('/contacto', 'pages.contacto')->name('contacto');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/mi-membresia', [MembershipController::class, 'index'])->name('mi-membresia.index');
    Route::get('/mi-membresia/crear', [MembershipController::class, 'create'])->name('mi-membresia.create');
    Route::post('/mi-membresia', [MembershipController::class, 'store'])->name('mi-membresia.store');
    Route::post('/mi-membresia/{membership}/pago-simulado', [MembershipController::class, 'simulatePayment'])->name('mi-membresia.pago-simulado');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'master'])->group(function () {
    Route::view('/', 'admin.dashboard')->name('dashboard');
    Route::resource('planes', PlanController::class)->except('show');
    Route::get('/miembros', [MemberController::class, 'index'])->name('miembros.index');
    Route::patch('/membresias/{membership}/cancelar', [MemberController::class, 'cancelMembership'])->name('membresias.cancelar');
});

require __DIR__.'/auth.php';
