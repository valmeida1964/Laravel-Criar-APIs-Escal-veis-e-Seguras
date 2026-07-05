<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
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
            // 'categories' => $categories,

            'categories' => CategoryResource::collection($categories),
            'totalCategories' => $categories->count(),
        ]);
    }

    public function listProducts()
    {
        $products = Product::with('category')->get();
        
        return ApiResponse::success([
            'products' => ProductResource::collection($products),
            'totalProducts' => $products->count(),
        ]);
    }
}
