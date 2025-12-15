<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ExamenController;

/*
|--------------------------------------------------------------------------
| RUTA PÚBLICA
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (LOGIN OBLIGATORIO)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | MÓDULO EXÁMENES
    |--------------------------------------------------------------------------
    */

    // Listado + resultados
    Route::get('/examenes', [ExamenController::class, 'index'])
        ->name('examenes.index');

    // Guardar resultado (APROBADO / NO APROBADO)
    Route::post('/examenes/resultado', [ExamenController::class, 'resultado'])
        ->name('examenes.resultado');

    // Exportar Excel
    Route::get('/examenes/exportar', [ExamenController::class, 'exportar'])
        ->name('examenes.exportar');
});

require __DIR__.'/auth.php';
