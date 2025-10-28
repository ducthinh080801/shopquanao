<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Áo Thun',
                'slug' => 'ao-thun',
                'description' => 'Áo thun nam nữ chất lượng cao, form đẹp',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Áo Sơ Mi',
                'slug' => 'ao-so-mi',
                'description' => 'Áo sơ mi công sở, dạo phố',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Quần Jean',
                'slug' => 'quan-jean',
                'description' => 'Quần jean nam nữ nhiều kiểu dáng',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Quần Kaki',
                'slug' => 'quan-kaki',
                'description' => 'Quần kaki thanh lịch, thoải mái',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Áo Khoác',
                'slug' => 'ao-khoac',
                'description' => 'Áo khoác đa dạng phong cách',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Đầm Váy',
                'slug' => 'dam-vay',
                'description' => 'Váy đầm nữ xinh xắn, duyên dáng',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('categories')->insertBatch($data);
    }
}
