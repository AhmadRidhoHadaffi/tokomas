<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomOrderHistoriesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'custom_order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Masuk',
                    'Diproses',
                    'Dalam Pembuatan',
                    'Selesai',
                    'Diambil'
                ],
            ],

            'changed_by' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);

        // Index FK
        $this->forge->addKey('custom_order_id');
        $this->forge->addKey('changed_by');

        // FK ke custom_orders
        $this->forge->addForeignKey(
            'custom_order_id',
            'custom_orders',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // FK ke users
        $this->forge->addForeignKey(
            'changed_by',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('custom_order_histories', true);
    }

    public function down()
    {
        $this->forge->dropTable('custom_order_histories', true);
    }
}
