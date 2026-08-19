<?php

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ChatbotController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/productos', [ProductoController::class, 'index'])->name('productos.index');
Route::post('/productos', [ProductoController::class, 'store'])->name('productos.store');

// Rutas del chatbot
Route::get('/chat', [ChatbotController::class, 'index'])->name('chat.index');
Route::post('/chat/send', [ChatbotController::class, 'sendMessage'])->name('chat.send');
