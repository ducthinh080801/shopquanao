<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMomoToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'momo_phone' => [
                'type' => 'VARCHAR',
                'constraint' => 15,
                'null' => true,
                'after' => 'stripe_card_name',
            ],
            'momo_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'momo_phone',
            ],
        ];
        
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'momo_phone',
            'momo_name',
        ]);
    }
}
