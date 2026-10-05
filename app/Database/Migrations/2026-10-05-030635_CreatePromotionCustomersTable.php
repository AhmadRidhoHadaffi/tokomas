<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionCustomersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'promotion_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],
        ]);

        // Primary key gabungan
        $this->forge->addKey(
            ['promotion_id', 'customer_id'],
            true
        );

        // Foreign key ke promotions
        $this->forge->addForeignKey(
            'promotion_id',
            'promotions',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Foreign key ke customers
        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->createTable('promotion_customers', true);
    }

    public function down()
    {
        $this->forge->dropTable('promotion_customers', true);
    }
}
