<?php

use App\Http\Controllers\ProductSearchController;
use Illuminate\Support\Facades\Route;

Route::post('/search-products', [ProductSearchController::class, 'search']);
