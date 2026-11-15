<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOtpCodesTable extends Migration
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
            'phone' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'purpose' => [
                'type'       => 'ENUM',
                'constraint' => ['register', 'reset_password'],
            ],
            'code_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => 64,
            ],
            'attempts' => [
                'type'     => 'TINYINT',
                'unsigned' => true,
                'default'  => 0,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
            ],
            'last_sent_at' => [
                'type' => 'DATETIME',
            ],
            'consumed_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey(['user_id', 'purpose']);
        $this->forge->addKey('expires_at');
        $this->forge->createTable('otp_codes', true);
    }

    public function down()
    {
        $this->forge->dropTable('otp_codes', true);
    }
}
