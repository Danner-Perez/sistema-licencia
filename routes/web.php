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
| RUTAS PROTEGIDAS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // DASHBOARD
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | POSTULANTES
    | admin + examinador
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:admin,examinador')->group(function () {
        Route::resource('postulantes', PostulanteController::class)
            ->except(['show']);
    });

    /*
    |--------------------------------------------------------------------------
    | ASISTENCIAS
    | asistencia + admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:asistencia,admin')
        ->prefix('asistencias')
        ->name('asistencias.')
        ->group(function () {

            Route::get('/', [AsistenciaController::class, 'index'])
                ->name('index');

            Route::post('/marcar', [AsistenciaController::class, 'marcarAjax'])
                ->name('marcar');
        });

    /*
    |--------------------------------------------------------------------------
    | VERIFICACIÓN
    | examinador + admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:examinador,admin')
        ->prefix('verificaciones')
        ->name('verificaciones.')
        ->group(function () {

            // Listado de verificaciones
            Route::get('/', [VerificacionController::class, 'index'])->name('index');

            // Crear verificación
            Route::get('/crear', [VerificacionController::class, 'create'])->name('create');
            Route::post('/', [VerificacionController::class, 'store'])->name('store');

            // Buscar postulante (AJAX) — debe ir antes de las rutas con parámetros
            Route::get('/buscar-postulante', [VerificacionController::class, 'buscarPostulante'])
                ->name('buscarPostulante');

            // Editar / actualizar verificación
            Route::get('/{verificacion}/editar', [VerificacionController::class, 'edit'])->name('edit');
            Route::put('/{verificacion}', [VerificacionController::class, 'update'])->name('update');

            // Eliminar verificación
            Route::delete('/{verificacion}', [VerificacionController::class, 'destroy'])->name('destroy');
    });




    /*
    |--------------------------------------------------------------------------
    | EXÁMENES
    | examinador + admin
    |--------------------------------------------------------------------------
    */
    Route::middleware('rol:examinador,admin')
    ->prefix('examenes')
    ->name('examenes.')
    ->group(function () {

        // Vista principal de exámenes
        Route::get('/', [ExamenController::class, 'index'])
            ->name('index');

        // Registrar intento de examen
        Route::post('/registrar', [ExamenController::class, 'store'])
            ->name('store');

        // SOLO ADMIN
        Route::middleware('rol:admin')->group(function () {

            // Exportar resultados
            Route::get('/exportar', [ExamenController::class, 'exportar'])
                ->name('exportar');

        });
    });


    /*
    |--------------------------------------------------------------------------
    | PERFIL
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
