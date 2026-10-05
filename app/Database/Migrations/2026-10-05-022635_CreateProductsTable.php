<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateProductsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'code' => [
                'type'       => 'VARCHAR',
                'constraint' => 10,
            ],

            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],

            'category' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Cincin',
                    'Kalung',
                    'Gelang',
                    'Anting',
                    'Liontin'
                ],
            ],

            'purity' => [
                'type'       => 'ENUM',
                'constraint' => [
                    '24K',
                    '22K',
                    '18K',
                    '17K',
                    'Perak 925'
                ],
            ],

            'weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
            ],

            'size' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true,
            ],

            'model' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],

            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
            ],

            'stock' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,
                'default'  => 0,
            ],

            'image' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],

            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addUniqueKey('code');

        $this->forge->createTable('products', true);
    }

    public function down()
    {
        $this->forge->dropTable('products', true);
    }
}
