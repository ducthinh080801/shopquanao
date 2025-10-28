<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStripeCardToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'stripe_card_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'avatar',
            ],
            'stripe_last4' => [
                'type' => 'VARCHAR',
                'constraint' => 4,
                'null' => true,
                'after' => 'stripe_card_id',
            ],
            'stripe_brand' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'stripe_last4',
            ],
            'stripe_exp_month' => [
                'type' => 'INT',
                'constraint' => 2,
                'null' => true,
                'after' => 'stripe_brand',
            ],
            'stripe_exp_year' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
                'after' => 'stripe_exp_month',
            ],
            'stripe_card_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'stripe_exp_year',
            ],
        ];
        
        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', [
            'stripe_card_id',
            'stripe_last4',
            'stripe_brand',
            'stripe_exp_month',
            'stripe_exp_year',
            'stripe_card_name',
        ]);
    }
}
