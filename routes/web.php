<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

Route::resource('productos', ProductoController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('repartidores', RepartidorController::class);
Route::resource('pagos', PagoController::class);
