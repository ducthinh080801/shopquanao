<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call('UsersSeeder');
        $this->call('CategoriesSeeder');
        $this->call('ProductsSeeder');
        $this->call('OrdersSeeder');
        $this->call('ReviewsSeeder');
    }
}
