<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhoneVerifiedAtToUsers extends Migration
{
    public function up()
    {
        if (! $this->db->fieldExists('phone_verified_at', 'users')) {
            $this->forge->addColumn('users', [
                'phone_verified_at' => [
                    'type'  => 'DATETIME',
                    'null'  => true,
                    'after' => 'is_active',
                ],
            ]);
        }

        // Akun yang sudah ada sebelum fitur OTP dianggap sudah terverifikasi
        // supaya tidak terkunci dari proses masuk.
        $this->db->table('users')
            ->where('phone_verified_at', null)
            ->update(['phone_verified_at' => date('Y-m-d H:i:s')]);
    }

    public function down()
    {
        if ($this->db->fieldExists('phone_verified_at', 'users')) {
            $this->forge->dropColumn('users', 'phone_verified_at');
        }
    }
}
