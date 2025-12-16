<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\VerificacionController;
use App\Http\Controllers\ExamenController;

/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (AUTH + VERIFIED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD (TODOS)
    |--------------------------------------------------------------------------
    */
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | POSTULANTES
    | ADMIN + EXAMINADOR
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:admin,examinador')->group(function () {
        Route::resource('postulantes', PostulanteController::class);
    });

    /*
    |--------------------------------------------------------------------------
    | ASISTENCIAS
    | SOLO ASISTENCIA + ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:asistencia,admin')->prefix('asistencias')->name('asistencias.')->group(function () {
        Route::post('/marcar', [AsistenciaController::class, 'marcar'])
            ->name('marcar');
    });

    /*
    |--------------------------------------------------------------------------
    | VERIFICACIONES
    | EXAMINADOR + ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:examinador,admin')->prefix('verificaciones')->name('verificaciones.')->group(function () {
        Route::post('/registrar', [VerificacionController::class, 'registrar'])
            ->name('registrar');
    });

    /*
    |--------------------------------------------------------------------------
    | EXÁMENES
    | EXAMINADOR + ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:examinador,admin')->prefix('examenes')->name('examenes.')->group(function () {

        Route::get('/', [ExamenController::class, 'index'])
            ->name('index');

        Route::post('/resultado', [ExamenController::class, 'resultado'])
            ->name('resultado');

        // 👉 SOLO ADMIN EXPORTA
        Route::middleware('rol:admin')->get('/exportar', [ExamenController::class, 'exportar'])
            ->name('exportar');
    });

    /*
    |--------------------------------------------------------------------------
    | PERFIL (TODOS)
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
