<?php

namespace Database\Seeders;

use App\Models\SalesTarget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesTargetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesTarget::create([
            'user_id' => 13,
            'branch_id' => 9,
            'month' => '2025-07-01',
            'target_value' => 7,
        ]);
        SalesTarget::create([
            'user_id' => 15,
            'branch_id' => 27,
            'month' => '2025-07-01',
            'target_value' => 5,
        ]);
        SalesTarget::create([
            'user_id' => 18,
            'branch_id' => 27,
            'month' => '2025-07-01',
            'target_value' => 6,
        ]);
        SalesTarget::create([
            'user_id' => 20,
            'branch_id' => 27,
            'month' => '2025-07-01',
            'target_value' => 5,
        ]);
        SalesTarget::create([
            'user_id' => 14,
            'branch_id' => 25,
            'month' => '2025-07-01',
            'target_value' => 1,
        ]);
    }
}
