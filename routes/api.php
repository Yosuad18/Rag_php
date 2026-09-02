<?php

use App\Http\Controllers\ProductSearchController;
use Illuminate\Support\Facades\Route;

// Le asignamos el nombre 'api.search' para poder usar route() en Blade
Route::post('/search', [ProductSearchController::class, 'search'])->name('api.search');
