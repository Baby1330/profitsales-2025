<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'sku' => 'OSTK',
                'name' => 'Osteokom',
                'description' => 'Rangkaian suplemen sendi dan tulang, mengandung Glucosamine, Chondroitin, MSM, dll.',
                'price' => 137500.00,
                // 'cost' => dihitung otomatis nanti
                'stock' => 800,
            ],
            [
                'sku' => 'EVIT',
                'name' => 'EYEVIT',
                'description' => 'Suplemen kesehatan mata, membantu menjaga penglihatan.',
                'price' => 140000.00,
                'stock' => 1000,
            ],
            [
                'sku' => 'ALER',
                'name' => 'Alerhis Kaplet',
                'description' => 'Antihistamin untuk meredakan alergi seperti bersin, gatal, dan ruam.',
                'price' => 100000.00,
                'stock' => 0,
            ],
            [
                'sku' => 'FULZ',
                'name' => 'Fulaz',
                'description' => 'Suplemen atau obat yang diduga untuk imun, perlu info tambahan untuk kejelasan.',
                'price' => 70000.00,
                'stock' => 750,
            ],
            [
                'sku' => 'IMVK',
                'name' => 'Imunvit',
                'description' => 'Suplemen imun yang mendukung daya tahan tubuh.',
                'price' => 70000.00,
                'stock' => 90,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'cost' => round($product['price'] * 0.1, 2), // 10% dari price dibulatkan 2 desimal
                    'stock' => $product['stock'],
                ]
            );
        }
    }
}
