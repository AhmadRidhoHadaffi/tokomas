<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'message' => [
                'type'       => 'VARCHAR',
                'constraint' => 300,
            ],

            'target_type' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'semua',
                    'terpilih'
                ],
            ],

            'status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Draf',
                    'Terjadwal',
                    'Terkirim'
                ],
                'default' => 'Draf',
            ],

            'send_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);

        // Index user pembuat promosi
        $this->forge->addKey('user_id');

        // Foreign key ke users
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('promotions', true);
    }

    public function down()
    {
        $this->forge->dropTable('promotions', true);
    }
}
