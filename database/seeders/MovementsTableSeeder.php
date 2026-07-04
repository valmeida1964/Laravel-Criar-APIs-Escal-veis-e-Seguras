<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class MovementsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalMovements = 500;
        $movementDate = Carbon::create(2000, 1, 1, 0, 0, 0);
        
        $movements = [];

        for ($i = 1; $i <= $totalMovements; $i++) {
            $movements[] = [
                'product_id' => rand(1, 9), // Assuming product IDs range from 1 to 9
                'quantity' => rand(1, 100),
                'movement_type' => rand(0, 1) ? 'in' : 'out',
                'created_at' => $movementDate->format('Y-m-d H:i:s'),
                'updated_at' => $movementDate->format('Y-m-d H:i:s'),
            ];

            // increment date by random hours
            $hoursToAdd = rand(1, 6); // Random hours between 1 and 6
            $movementDate->modify("+{$hoursToAdd} hours");
        }

        // insert batch
        DB::table('movements')->insert($movements);
    }
}
