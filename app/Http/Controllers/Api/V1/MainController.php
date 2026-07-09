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
        $perPage = request()->get('per_page', 15); // 15 values per page
        $categories = Category::paginate($perPage);

        return ApiResponse::success([
            'categories' => CategoryResource::collection($categories),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'totalCategories' => $categories->count(),
            ]
        ]);
    }

    public function listProducts()
    {
        $perPage = request()->get('per_page', 15); // 15 values per page
        $products = Product::paginate($perPage);

        return ApiResponse::success([
            $products = ProductResource::collection($products),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'totalProducts' => $products->count(),
            ]
        ]);
    }
    public function listMovements()
    {
        $perPage = request()->get('per_page', 15); // 15 values per page
        $movements = Movement::paginate($perPage);

        return ApiResponse::success([
            'movements' => MovementResource::collection($movements),
            'pagination' => [
                'current_page' => $movements->currentPage(),
                'last_page' => $movements->lastPage(),
                'per_page' => $movements->perPage(),
                'totalMovements' => $movements->count(),
            ]
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

    public function listMovementsOrdered($field, $direction)
    {
        $validFields = ['id','product_id','quantity','movement_type','created_at','updated_at'];
        $validDirections = ['asc','desc'];

        // validate field
        if(!in_array($field, $validFields)) {
            return ApiResponse::error("Invalid field for ordering:{$field}", 400);
        }

        // validate direction
        if(!in_array($direction, $validDirections)) {
            return ApiResponse::error("Invalid direction for ordering:{$direction}", 400);
        }

        $perPage = request()->get('per_page', 15); // 15 values per page
        $movements = Movement::with('product.category')
                ->orderBy($field, $direction)
                ->paginate($perPage);

        return ApiResponse::success([
            'movements' => MovementResource::collection($movements),
            'pagination' => [
                'current_page' => $movements->currentPage(),
                'last_page' => $movements->lastPage(),
                'per_page' => $movements->perPage(),
                'totalMovements' => $movements->count(),
            ]
        ]);
    }

    public function createCategory(Request $request)
    {
        $data = $request->validate([
            'name' => 'string|max:50|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($data);

        return ApiResponse::success(
            new CategoryResource($category),
            'Category created successfully',
            201
         );
    }

    public function createProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'string|max:50|unique:products,name',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id'
        ]);

        $product = Product::create($data);

        return ApiResponse::success(
            new ProductResource($product),
            'Product created successfully',
            201
         );
    }
    public function createMovement(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'movement_type' => 'required|in:in,out',
        ]);

        $movement = Movement::create($data);

        return ApiResponse::success(
            new MovementResource($movement),
            'Movement created successfully',
            201
         );
    }

    public function updateCategory(Request $request, $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return ApiResponse::error("Category with ID {$id} not found.", 404);
        }

        $data = $request->validate([
            'name' => 'string|max:50|unique:categories,name,' . $id,
            'description' => 'nullable|string'

        ]);

        $category->update($data);

        return ApiResponse::success(
            new CategoryResource($category),
            "Category updated successfuly",
        );
    }

    public function updateProduct(Request $request, mixed $id)
    {

        $product = Product::find($id);
        if(!$product) {
            return ApiResponse::error("Product with ID {$id} not found.", 404);
        }

        $data = $request->validate([
            'name' => 'string|max:50|unique:products,name,' . $id,
            'description' => 'nullable|string',
            'category_id' => 'exists:categories,id'
        ]);

        $product->update($data);

        return ApiResponse::success(
            new CategoryResource($product),
            "Product updated successfuly",
        );
    }

    public function updateMovement(Request $request, mixed $id)
    {
        $movement = Movement::find($id);
        if(!$movement) {
            return ApiResponse::error("Movement with ID {$id} not found.", 404);
        }

        $data = $request->validate([
            'product_id' => 'integer|exists:products,id',
            'quantity' => 'integer|min:1',
            'movement_type' => 'in:in,out'
        ]);

        $movement->update($data);

        return ApiResponse::success(
            new MovementResource($movement),
            "Movement updated successfuly",
        );
    }

    public function deleteMovement(mixed $id)
    {
        $movement = Movement::find($id);
        if(!$movement) {
            return ApiResponse::error("Movement with ID {$id} not found.", 404);
        }

        $movement->delete();

        return ApiResponse::success(
            [],
            "Movement deleted successfuly"
        );
    }

    public function deleteProduct(mixed $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return ApiResponse::error("Product with ID {$id} not found.", 404);
        }

        $product->delete();

        return ApiResponse::success(
            [],
            "Product deleted successfuly"
        );
    }
    
    public function deleteCategory(mixed $id)
    {
        $category = Category::find($id);
        if(!$category) {
            return ApiResponse::error("Category with ID {$id} not found.", 404);
        }

        $category->delete();

        return ApiResponse::success(
            [],
            "Category deleted successfuly"
        );
    }
    
}
