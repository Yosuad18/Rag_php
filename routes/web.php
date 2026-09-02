<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProductSearchController; // Importación agregada
use App\Http\Controllers\RepartidorController;
use App\Http\Controllers\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

// Endpoint para el Chatbot IA con RAG (Vector Search)
Route::get('/consultar-ia', [ProductSearchController::class, 'search'])->name('consultar-ia');
Route::post('/consultar-ia', [ProductSearchController::class, 'search'])->name('consultar-ia');

// Recursos de la aplicación
Route::resource('productos', ProductoController::class);
Route::resource('usuarios', UsuarioController::class);
Route::resource('repartidores', RepartidorController::class);
Route::resource('pagos', PagoController::class);

