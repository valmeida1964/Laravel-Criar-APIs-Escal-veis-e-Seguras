<?php

use App\Http\Controllers\Api\V1\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/status', [MainController::class, 'status']);

Route::get('/categories', [MainController::class, 'listCategories']);
Route::get('/products',   [MainController::class, 'listProducts']);


