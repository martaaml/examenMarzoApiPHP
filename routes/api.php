<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CancionController;
    // Rutas para la autenticación
    Route::get('/login', 'App\Http\Controllers\AuthController@login'); // Login
    Route::get('/register', 'App\Http\Controllers\AuthController@register'); // Registro
    Route::get('/verify/{token}', 'App\Http\Controllers\AuthController@verify'); // Verificación de cuenta
   
    
// Rutas para las inscripciones
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/listas', [CancionController::class, 'index']); // Listar todas las inscripciones
    Route::post('/listas', [CancionController::class, 'store']); // Crear nueva inscripción
    Route::get('/listas/{id_cancion}', [CancionController::class, 'show']); // Ver detalles de inscripción
    Route::delete('/listas/{id_cancion}', [CancionController::class, 'destroy']); // Eliminar inscripción
});

// Rutas protegidas por middleware de autenticación
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
