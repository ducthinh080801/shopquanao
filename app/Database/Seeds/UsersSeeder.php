<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@example.com',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name' => 'Admin',
                'phone' => '0123456789',
                'address' => '123 Admin Street, Ho Chi Minh City',
                'role' => 'admin',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'full_name' => 'Nguyễn Văn A',
                'phone' => '0987654321',
                'address' => '456 User Street, Ho Chi Minh City',
                'role' => 'user',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username' => 'user2',
                'email' => 'user2@example.com',
                'password' => password_hash('user123', PASSWORD_DEFAULT),
                'full_name' => 'Trần Thị B',
                'phone' => '0912345678',
                'address' => '789 Customer Avenue, Hanoi',
                'role' => 'user',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
