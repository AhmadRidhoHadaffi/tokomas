<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStoreSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'TINYINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'store_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
            ],

            'whatsapp' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
            ],

            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],

            'notify_status_change' => [
                'type'     => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default'  => 1,
            ],

            'notify_payment' => [
                'type'     => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default'  => 1,
            ],

            'notify_low_stock' => [
                'type'     => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default'  => 1,
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->createTable('store_settings', true);
    }

    public function down()
    {
        $this->forge->dropTable('store_settings', true);
    }
}
