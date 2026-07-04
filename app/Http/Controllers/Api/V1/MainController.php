<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ApiResponse;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function status()
    {
        return ApiResponse::success([
            'currentStatusString' => 'API is running',
            'serverDate' => now()->toDateString(),
            'serverTime' => now()->toTimeString(),
            'serverTimestamp' => now()->timestamp,
            'serverTimezone' => now()->timezoneName,
            'apiVersion' => 'v1 '

        ]);
    }

    public function listCategories()
    {
        $categories = Category::all();

        return ApiResponse::success([
            'categories' => $categories
        ]);
    }

    public function listProducts()
    {
        $products = Product::all();

        return ApiResponse::success([
            'products' => $products
        ]);
    }
}
