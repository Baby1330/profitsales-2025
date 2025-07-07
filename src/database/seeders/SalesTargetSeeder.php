<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Sales;
use App\Models\SalesTarget;
use Carbon\Carbon;

class SalesTargetSeeder extends Seeder
{
    public function run(): void
    {
        $month = now()->month;
        $year = now()->year;

        $salesList = Sales::all();

        foreach ($salesList as $sales) {
            SalesTarget::firstOrCreate([
                'sales_id' => $sales->id,
                'year' => $year,
                'month' => $month,
            ], [
                'targetprod' => 5,
            ]);
        }

        $this->command->info("🎯 Target berhasil digenerate untuk bulan $month tahun $year.");
    }
}
