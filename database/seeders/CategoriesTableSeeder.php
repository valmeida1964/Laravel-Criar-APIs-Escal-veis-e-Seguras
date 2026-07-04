<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Cereais', 'description' => 'Todo os tipos de cereais', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Frutas', 'description' => 'Variedade de frutas frescas', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ferramentas', 'description' => 'Ferramentas manuais e elétricas', 'created_at' => now(), 'updated_at' => now()],
        ];
        foreach ($categories as $category) {
            DB::table('categories')->insert($category);
        }
    }
}