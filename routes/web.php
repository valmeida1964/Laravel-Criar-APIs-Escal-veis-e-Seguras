<?php

use App\Services\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::fallback(function(){
    return ApiResponse::error('Endpoint not found', 404);
});