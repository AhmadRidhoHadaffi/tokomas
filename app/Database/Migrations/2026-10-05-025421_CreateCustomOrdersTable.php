<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomOrdersTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'order_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
            ],

            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'jewelry_type' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Cincin',
                    'Kalung',
                    'Gelang',
                    'Anting',
                    'Liontin'
                ],
            ],

            'model' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            'engraving_name' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
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
                'null'       => true,
            ],

            'estimated_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
            ],

            'dp' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
                'default'    => 0,
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
                'default' => 'Masuk',
            ],

            'order_date' => [
                'type' => 'DATE',
            ],

            'due_date' => [
                'type' => 'DATE',
                'null' => true,
            ],

            'reference_image' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],

            'notes' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
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

        // Nomor pesanan harus unik
        $this->forge->addUniqueKey('order_no');

        // Index customer
        $this->forge->addKey('customer_id');

        // Index status
        $this->forge->addKey('status');

        // Index tanggal pesanan
        $this->forge->addKey('order_date');

        // Foreign key ke customers
        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('custom_orders', true);
    }

    public function down()
    {
        $this->forge->dropTable('custom_orders', true);
    }
}
