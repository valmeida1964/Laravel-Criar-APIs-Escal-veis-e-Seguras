<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // cereais
            ['name' => 'Arroz', 'description' => 'Arroz branco tipo 1', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Milho', 'description' => 'Milho para pipoca', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Trigo', 'description' => 'Trigo integral', 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            // frutas
            ['name' => 'Maçã', 'description' => 'Maçã vermelha fresca', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Banana', 'description' => 'Banana tropical', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Laranja', 'description' => 'Laranja portuguesa', 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            // ferramentas
            ['name' => 'Martelo', 'description' => 'Martelo de aço com cabo de madeira', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Chave de fenda', 'description' => 'Chave de fenda Phillips', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alicate', 'description' => 'Alicate universal', 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],            
        ];

        foreach($products as $product) {
            DB::table('products')->insert($product);
        }
    }
}
