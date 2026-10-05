<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'invoice_no' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],

            'customer_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'user_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'transaction_date' => [
                'type' => 'DATETIME',
            ],

            'discount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
                'default'    => 0,
            ],

            'total' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
            ],

            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Tunai',
                    'Transfer',
                    'QRIS'
                ],
            ],

            'payment_status' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Lunas',
                    'Menunggu'
                ],
                'default' => 'Lunas',
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);

        // Unique invoice
        $this->forge->addUniqueKey('invoice_no');

        // Index transaction date
        $this->forge->addKey('transaction_date');

        // Foreign key customer
        $this->forge->addForeignKey(
            'customer_id',
            'customers',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // Foreign key user/kasir
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('transactions', true);
    }

    public function down()
    {
        $this->forge->dropTable('transactions', true);
    }
}
