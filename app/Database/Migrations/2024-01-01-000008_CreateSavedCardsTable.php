<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSavedCardsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'stripe_card_id' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'last4' => [
                'type' => 'VARCHAR',
                'constraint' => 4,
            ],
            'brand' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
            ],
            'exp_month' => [
                'type' => 'INT',
                'constraint' => 2,
            ],
            'exp_year' => [
                'type' => 'INT',
                'constraint' => 4,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'is_default' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('saved_cards');
    }

    public function down()
    {
        $this->forge->dropTable('saved_cards');
    }
}
