<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ReviewsSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'product_id' => 1,
                'user_id' => 2,
                'order_id' => 1,
                'rating' => 5,
                'comment' => 'Sản phẩm rất đẹp, chất lượng tốt, giao hàng nhanh!',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'product_id' => 2,
                'user_id' => 3,
                'order_id' => null,
                'rating' => 5,
                'comment' => 'Áo đẹp, form chuẩn, chất vải mát',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
            ],
            [
                'product_id' => 6,
                'user_id' => 2,
                'order_id' => 1,
                'rating' => 5,
                'comment' => 'Quần jean chất lượng, giá cả hợp lý',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
            ],
            [
                'product_id' => 10,
                'user_id' => 3,
                'order_id' => null,
                'rating' => 4,
                'comment' => 'Áo khoác ấm, đẹp nhưng hơi dày',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-1 days')),
            ],
            [
                'product_id' => 13,
                'user_id' => 2,
                'order_id' => null,
                'rating' => 5,
                'comment' => 'Váy đẹp lắm, mặc rất thoải mái',
                'is_approved' => 1,
                'created_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
                'updated_at' => date('Y-m-d H:i:s', strtotime('-4 days')),
            ],
        ];

        $this->db->table('reviews')->insertBatch($data);
    }
}
