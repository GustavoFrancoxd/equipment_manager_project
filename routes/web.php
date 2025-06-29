<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\TipoEquipoController;
use App\Http\Controllers\UserController;

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('equipos.index');
    });
    
    Route::resource('equipos', EquipoController::class);
    Route::resource('tipo-equipos', TipoEquipoController::class);
    Route::resource('users', UserController::class)->middleware('can:admin');
});