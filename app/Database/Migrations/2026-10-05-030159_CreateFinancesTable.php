<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFinancesTable extends Migration
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

            'transaction_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'custom_order_id' => [
                'type'     => 'INT',
                'unsigned' => true,
                'null'     => true,
            ],

            'type' => [
                'type'       => 'ENUM',
                'constraint' => [
                    'Pemasukan',
                    'Pengeluaran'
                ],
            ],

            'category' => [
                'type'       => 'VARCHAR',
                'constraint' => 30,
            ],

            'description' => [
                'type'       => 'VARCHAR',
                'constraint' => 150,
            ],

            'amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '12,0',
            ],

            'entry_date' => [
                'type' => 'DATE',
            ],

            'created_at' => [
                'type' => 'DATETIME',
            ],

            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);

        // Index
        $this->forge->addKey(['type', 'entry_date']);
        $this->forge->addKey('user_id');
        $this->forge->addKey('custom_order_id');

        // Satu transaksi penjualan
        // maksimal menghasilkan satu catatan pemasukan
        $this->forge->addUniqueKey('transaction_id');

        // FK user
        $this->forge->addForeignKey(
            'user_id',
            'users',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // FK transaksi
        $this->forge->addForeignKey(
            'transaction_id',
            'transactions',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        // FK pesanan custom
        $this->forge->addForeignKey(
            'custom_order_id',
            'custom_orders',
            'id',
            'RESTRICT',
            'CASCADE'
        );

        $this->forge->createTable('finances', true);
    }

    public function down()
    {
        $this->forge->dropTable('finances', true);
    }
}
