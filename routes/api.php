<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\TipoEquipoController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('equipos', EquipoController::class);
    Route::apiResource('tipo-equipos', TipoEquipoController::class);
});