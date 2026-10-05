<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateNotificationsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'promotion_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'type' => [
                'type' => 'ENUM',
                'constraint' => [
                    'Status Pesanan',
                    'Pembayaran',
                    'Promosi'
                ],
            ],

            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'message' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
            ],

            'is_read' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'unsigned'   => true,
                'default'    => 0,
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['customer_id', 'is_read']);
        $this->forge->addKey('created_at');
        $this->forge->addKey('promotion_id');

        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->forge->addForeignKey(
            'promotion_id',
            'promotions',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('notifications', true);
    }

    public function down()
    {
        $this->forge->dropTable('notifications', true);
    }
}
