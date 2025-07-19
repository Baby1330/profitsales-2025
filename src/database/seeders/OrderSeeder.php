<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Sales;
use App\Models\SalesCommissions;
use Illuminate\Database\Seeder;
use App\Enums\OrderStatus;
use App\Models\OrderFlow;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::first();
        $sales = Sales::whereNotNull('user_id')->first(); // pastikan ada user_id
        $product = Product::first();

        if (!$client || !$sales || !$product) {
            $this->command->error('Missing required related data: client, sales, or product.');
            return;
        }

        // // Create order
        // $order = Order::create([
        //     'client_id'     => $client->id,
        //     'sales_id'      => $sales->id,
        //     'order_number'  => 'ORD-0001',
        //     'category'      => 'PO',
        //     'status'        => OrderStatus::ConvertedToPO,
        //     'total'         => 50000,
        //     'profit'        => 0,
        //     'sales_profit'  => 0,
        //     'notes'         => 'Initial sales order.',
        // ]);

        // // Add one order detail
        // OrderDetail::create([
        //     'order_id'   => $order->id,
        //     'product_id' => $product->id,
        //     'quantity'   => 5,
        //     'price'      => 10000,
        //     'subtotal'   => 50000,
        // ]);

        $ordersData = [
            [
                'order' => [
                    'id' => 1,
                    'created_by' => null,
                    'client_id' => 1,
                    'sales_id' => 1,
                    'order_number' => 'INV-00001',
                    'category' => 'PO',
                    'total' => 50000.00,
                    'profit' => 50000.00,
                    'sales_profit' => 5000.00,
                    'status' => 'converted_to_po',
                    'notes' => 'Initial sales order.',
                    'created_at' => '2025-07-12 12:29:03',
                    'updated_at' => '2025-07-12 12:29:03',
                ],
                'detail' => [
                    'order_id' => 1,
                    'product_id' => 1,
                    'quantity' => 5,
                    'price' => 10000.00,
                    'subtotal' => 50000.00,
                    'created_at' => '2025-07-12 12:29:03',
                    'updated_at' => '2025-07-12 12:29:03',
                ]
            ],
            [
                'order' => [
                    'id' => 2,
                    'created_by' => 12,
                    'client_id' => 5,
                    'sales_id' => 2,
                    'order_number' => 'INV-00002',
                    'category' => 'PO',
                    'total' => 3220000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'converted_to_po',
                    'notes' => null,
                    'created_at' => '2025-07-12 12:38:01',
                    'updated_at' => '2025-07-15 08:51:05',
                ],
                'detail' => [
                    'order_id' => 2,
                    'product_id' => 2,
                    'quantity' => 15,
                    'price' => 140000.00,
                    'subtotal' => 2100000.00,
                    'created_at' => '2025-07-12 12:38:01',
                    'updated_at' => '2025-07-12 12:38:01',
                ]
            ],
            [
                'order' => [
                    'id' => 3,
                    'created_by' => 19,
                    'client_id' => 6,
                    'sales_id' => 8,
                    'order_number' => 'INV-00003',
                    'category' => 'PO',
                    'total' => 840000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'converted_to_po',
                    'notes' => null,
                    'created_at' => '2025-07-12 15:41:12',
                    'updated_at' => '2025-07-12 16:20:33',
                ],
                'detail' => [
                    'order_id' => 3,
                    'product_id' => 5,
                    'quantity' => 16,
                    'price' => 70000.00,
                    'subtotal' => 1120000.00,
                    'created_at' => '2025-07-12 12:38:01',
                    'updated_at' => '2025-07-12 12:38:01',
                ]
            ],
            [
                'order' => [
                    'id' => 4,
                    'created_by' => 3,
                    'client_id' => 2,
                    'sales_id' => 1,
                    'order_number' => 'INV-00004',
                    'category' => 'SO',
                    'total' => 350000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'pending',
                    'notes' => null,
                    'created_at' => '2025-07-12 19:14:41',
                    'updated_at' => '2025-07-12 19:14:41',
                ],
                'detail' => [
                    'order_id' => 4,
                    'product_id' => 2,
                    'quantity' => 6,
                    'price' => 140000.00,
                    'subtotal' => 840000.00,
                    'created_at' => '2025-07-12 15:41:12',
                    'updated_at' => '2025-07-12 15:41:12',
                ]
            ],
            [
                'order' => [
                    'id' => 5,
                    'created_by' => 24,
                    'client_id' => 2,
                    'sales_id' => 1,
                    'order_number' => 'INV-00005',
                    'category' => 'SO',
                    'total' => 2000000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'approved',
                    'notes' => 'DataTest',
                    'created_at' => '2025-07-15 21:44:34',
                    'updated_at' => '2025-07-15 22:26:36',
                ],
                'detail' => [
                    'order_id' => 5,
                    'product_id' => 4,
                    'quantity' => 5,
                    'price' => 70000.00,
                    'subtotal' => 350000.00,
                    'created_at' => '2025-07-12 19:14:41',
                    'updated_at' => '2025-07-12 19:14:41',
                ]
            ],
            [
                'order' => [
                    'id' => 6,
                    'created_by' => 25,
                    'client_id' => 6,
                    'sales_id' => 6,
                    'order_number' => 'INV-00006',
                    'category' => 'SO',
                    'total' => 560000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'reject',
                    'notes' => null,
                    'created_at' => '2025-07-15 21:46:40',
                    'updated_at' => '2025-07-15 21:51:13',
                ],
                'detail' => [
                    'order_id' => 6,
                    'product_id' => 6,
                    'quantity' => 5,
                    'price' => 120000.00,
                    'subtotal' => 600000.00,
                    'created_at' => '2025-07-15 21:44:34',
                    'updated_at' => '2025-07-15 21:44:34',
                ]
            ],
            [
                'order' => [
                    'id' => 7,
                    'created_by' => 25,
                    'client_id' => 6,
                    'sales_id' => 3,
                    'order_number' => 'INV-00007',
                    'category' => 'PO',
                    'total' => 275000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'converted_to_po',
                    'notes' => null,
                    'created_at' => '2025-07-15 21:48:09',
                    'updated_at' => '2025-07-15 21:56:49',
                ],
                'detail' => [
                    'order_id' => 7,
                    'product_id' => 2,
                    'quantity' => 10,
                    'price' => 140000.00,
                    'subtotal' => 1400000.00,
                    'created_at' => '2025-07-15 21:44:34',
                    'updated_at' => '2025-07-15 21:44:34',
                ]
            ],
            [
                'order' => [
                    'id' => 8,
                    'created_by' => 24,
                    'client_id' => 2,
                    'sales_id' => 1,
                    'order_number' => 'INV-00008',
                    'category' => 'SO',
                    'total' => 680000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'pending',
                    'notes' => null,
                    'created_at' => '2025-07-15 22:15:10',
                    'updated_at' => '2025-07-15 22:15:10',
                ],
                'detail' => [
                    'order_id' => 8,
                    'product_id' => 5,
                    'quantity' => 8,
                    'price' => 70000.00,
                    'subtotal' => 560000.00,
                    'created_at' => '2025-07-15 21:46:40',
                    'updated_at' => '2025-07-15 21:46:40',
                ]
            ],
            [
                'order' => [
                    'id' => 9,
                    'created_by' => 25,
                    'client_id' => 6,
                    'sales_id' => 3,
                    'order_number' => 'INV-00009',
                    'category' => 'SO',
                    'total' => 70000.00,
                    'profit' => 0.00,
                    'sales_profit' => 0.00,
                    'status' => 'pending',
                    'notes' => null,
                    'created_at' => '2025-07-16 19:03:11',
                    'updated_at' => '2025-07-16 19:03:11',
                ],
                'detail' => [
                    'order_id' => 9,
                    'product_id' => 1,
                    'quantity' => 2,
                    'price' => 137500.00,
                    'subtotal' => 275000.00,
                    'created_at' => '2025-07-15 21:48:09',
                    'updated_at' => '2025-07-15 21:48:09',
                ]
            ],
        ];

        foreach ($ordersData as $data) {
            $order = Order::create($data['order']);
            OrderDetail::create(array_merge($data['detail'], ['order_id' => $order->id]));

            // Komisi & flow (optional)
            $status = $data['order']['status'];
            $salesProfit = $data['order']['total'];
            
            if ($status === 'converted_to_po') {
                SalesCommissions::create([
                    'sales_id' => $order->sales_id,
                    'order_id' => $order->id,
                    'amount' => round($salesProfit * 0.10, 2),
                ]);
            }

            OrderFlow::create([
                'order_id' => $order->id,
                'user_id' => $order->sales_id,
                'from_status' => null,
                'to_status' => $data['order']['status'],
                'notes' => 'Seeded status change.',
            ]);
        }        

        // // Calculate profits
        // $profit = $order->calculateProfit();
        // $salesProfit = $order->calculateSalesProfit();

        // $order->update([
        //     'profit'       => $profit,
        //     'sales_profit' => $salesProfit,
        // ]);

        // // Commission
        // SalesCommissions::create([
        //     'sales_id' => $sales->id,
        //     'order_id' => $order->id,
        //     'amount'   => $salesProfit,
        // ]);


        // OrderFlow::create([
        //     'order_id'    => $order->id,
        //     'user_id'    => 2, // ✅ fixed: use sales_id not user_id
        //     'from_status' => null,
        //     'to_status'   => OrderStatus::ConvertedToPO,
        //     'notes'       => 'Auto-converted to PO during seeding.',
        // ]);
    }
}
