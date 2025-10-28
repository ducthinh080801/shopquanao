<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class OrdersSeeder extends Seeder
{
    public function run()
    {
        // Sample orders
        $orders = [
            [
                'user_id' => 2,
                'order_number' => 'ORD-' . date('Ymd') . '-0001',
                'total_amount' => 500000,
                'status' => 'completed',
                'shipping_address' => '456 User Street, Ho Chi Minh City',
                'shipping_phone' => '0987654321',
                'shipping_name' => 'Nguyễn Văn A',
                'notes' => 'Giao hàng giờ hành chính',
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'user_id' => 3,
                'order_number' => 'ORD-' . date('Ymd') . '-0002',
                'total_amount' => 830000,
                'status' => 'processing',
                'shipping_address' => '789 Customer Avenue, Hanoi',
                'shipping_phone' => '0912345678',
                'shipping_name' => 'Trần Thị B',
                'notes' => null,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
        ];

        foreach ($orders as $order) {
            $this->db->table('orders')->insert($order);
        }

        // Sample order items
        $orderItems = [
            [
                'order_id' => 1,
                'product_id' => 1,
                'product_name' => 'Áo Thun Basic Trắng',
                'price' => 120000,
                'quantity' => 2,
                'size' => 'L',
                'color' => 'Trắng',
                'subtotal' => 240000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'order_id' => 1,
                'product_id' => 6,
                'product_name' => 'Quần Jean Slim Fit',
                'price' => 380000,
                'quantity' => 1,
                'size' => '30',
                'color' => 'Xanh đậm',
                'subtotal' => 380000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-7 days')),
            ],
            [
                'order_id' => 2,
                'product_id' => 2,
                'product_name' => 'Áo Thun Polo Nam',
                'price' => 200000,
                'quantity' => 1,
                'size' => 'L',
                'color' => 'Xanh',
                'subtotal' => 200000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
            [
                'order_id' => 2,
                'product_id' => 10,
                'product_name' => 'Áo Khoác Hoodie Unisex',
                'price' => 320000,
                'quantity' => 1,
                'size' => 'XL',
                'color' => 'Đen',
                'subtotal' => 320000,
                'created_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
            ],
        ];

        $this->db->table('order_items')->insertBatch($orderItems);
    }
}
