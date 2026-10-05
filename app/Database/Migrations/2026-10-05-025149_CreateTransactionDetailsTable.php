<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransactionDetailsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'transaction_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'product_id' => [
                'type'     => 'INT',
                'unsigned' => true,
            ],

            'quantity' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,
            ],

            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
            ],
        ]);

        $this->forge->addKey('id', true);

        // Satu produk hanya muncul satu kali
        // dalam satu transaksi.
        $this->forge->addUniqueKey(
            ['transaction_id', 'product_id']
        );

        // FK ke transactions
        $this->forge->addForeignKey(
            'transaction_id',
            'transactions',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // FK ke products
        $this->forge->addForeignKey(
            'product_id',
            'products',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('transaction_details', true);
    }

    public function down()
    {
        $this->forge->dropTable('transaction_details', true);
    }
}
