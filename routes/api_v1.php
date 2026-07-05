<?php

use App\Http\Controllers\Api\V1\MainController;
use Illuminate\Support\Facades\Route;

Route::get('/status', [MainController::class, 'status']);

Route::get('/categories', [MainController::class, 'listCategories']);
Route::get('/products',   [MainController::class, 'listProducts']);
Route::get('/movements',  [MainController::class, 'listMovements']);

Route::get('categories/{id}',  [MainController::class, 'getCategory']);
Route::get('products/{id}',    [MainController::class, 'getProduct']);

Route::get('categories/{id}/products',              [MainController::class, 'getProductByCategory']);
Route::get('movements/ordered/{field}/{direction}', [MainController::class, 'listMovementsOrdered']);

