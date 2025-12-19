<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\VerificacionController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD SOLO ADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::get('/dashboard/data', [DashboardController::class, 'data'])
            ->name('dashboard.data');

        /*
        |--------------------------------------------------------------------------
        | ADMINISTRACIÓN DE USUARIOS
        |--------------------------------------------------------------------------
        */
        Route::middleware(['auth','rol:admin'])->prefix('admin')->name('admin.')->group(function(){
            Route::resource('users', App\Http\Controllers\Admin\UserController::class)->except(['show']);
        });

    });

    /*
    |--------------------------------------------------------------------------
    | POSTULANTES (admin + examinador)
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:admin,examinador')->group(function () {
        Route::resource('postulantes', PostulanteController::class)
            ->except(['show']);

        Route::get('/postulantes/buscar-dni', [PostulanteController::class, 'buscarPorDni'])
            ->name('postulantes.buscar-dni');
    });

    /*
    |--------------------------------------------------------------------------
    | ASISTENCIAS (admin + asistencia)
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:asistencia,admin')
        ->prefix('asistencias')
        ->name('asistencias.')->group(function () {
            Route::get('/', [AsistenciaController::class, 'index'])->name('index');
            Route::post('/marcar', [AsistenciaController::class, 'marcar'])->name('marcar');
        });

    /*
    |--------------------------------------------------------------------------
    | VERIFICACIONES (admin + examinador)
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:examinador,admin')
        ->prefix('verificaciones')
        ->name('verificaciones.')->group(function () {
            Route::get('/', [VerificacionController::class, 'index'])->name('index');
            Route::get('/crear', [VerificacionController::class, 'create'])->name('create');
            Route::post('/', [VerificacionController::class, 'store'])->name('store');

            Route::get('/buscar-postulante', [VerificacionController::class, 'buscarPostulante'])
                ->name('buscarPostulante');

            Route::get('/{verificacion}/editar', [VerificacionController::class, 'edit'])->name('edit');
            Route::put('/{verificacion}', [VerificacionController::class, 'update'])->name('update');
            Route::delete('/{verificacion}', [VerificacionController::class, 'destroy'])->name('destroy');
        });

    /*
    |--------------------------------------------------------------------------
    | EXÁMENES (admin + examinador)
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:examinador,admin')
        ->prefix('examenes')
        ->name('examenes.')->group(function () {
            Route::get('/', [ExamenController::class, 'index'])->name('index');
            Route::get('/registrar', [ExamenController::class, 'create'])->name('create');
            Route::post('/registrar', [ExamenController::class, 'store'])->name('store');

            Route::get('/buscar', [ExamenController::class, 'buscarPostulante'])->name('buscar');
            Route::get('/exportar-hoy', [ExamenController::class, 'exportarHoy'])->name('exportarHoy');

            Route::get('/{examen}/edit', [ExamenController::class, 'edit'])->name('edit');
            Route::put('/{examen}', [ExamenController::class, 'update'])->name('update');
        });

    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
