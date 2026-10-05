<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomersTable extends Migration
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
                'null'     => true,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],

            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 200,
                'null'       => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);

        // user_id harus unik karena satu user
        // hanya boleh terhubung ke satu customer
        $this->forge->addUniqueKey('user_id');

        // Nomor HP customer harus unik
        $this->forge->addUniqueKey('phone');

        // Relasi customers -> users
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->forge->createTable('customers', true);
    }

    public function down()
    {
        $this->forge->dropTable('customers', true);
    }
}
