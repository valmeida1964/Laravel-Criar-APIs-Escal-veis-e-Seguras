<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\MovementResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Movement;
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
    public function listMovements()
    {
        $movements = Movement::with('product.category')->get();
      
        return ApiResponse::success([
            'movements' => MovementResource::collection($movements),
            'totalMovements' => $movements->count(),
        ]);
    }
    
    public function getCategory(string $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return ApiResponse::error("Category with ID {$id} not found", 404);
        }

        return ApiResponse::success([
            'category' => new CategoryResource($category) 
        ]);
    }

    public function getProduct(string $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return ApiResponse::error("Product with ID {$id} not found", 404);
        }

        return ApiResponse::success([
            'product' => new ProductResource($product) 
        ]);
    }

    public function getProductByCategory(string $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return ApiResponse::error("Category with ID {$id} not found.", 404);
        }

        $products = Product::where('category_id', $id)
            ->get()
            ->toResourceCollection(ProductResource::class)
            ->resolve();

        $products = array_map(function($product) {
            unset($product['category']);
            return $product;
        }, $products);
        
        return ApiResponse::success([
            'category' => new CategoryResource($category),
            'products' => $products,
            'totalProducts' => count($products),
        ]);
    }

}
