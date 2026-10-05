<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGoldPricesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'purity' => [
                'type' => 'ENUM',
                'constraint' => [
                    '24K',
                    '22K',
                    '18K',
                    '17K'
                ],
            ],

            'price_per_gram' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,0',
            ],

            'updated_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('purity', true);

        $this->forge->addForeignKey(
            'updated_by',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('gold_prices', true);
    }

    public function down()
    {
        $this->forge->dropTable('gold_prices', true);
    }
}
