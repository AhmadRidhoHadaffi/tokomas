<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        $now = date('Y-m-d H:i:s');

        $this->db->table('users')->insertBatch([
            [
                'id' => 1,
                'name' => 'Owner Test',
                'email' => 'owner@test.local',
                'password' => password_hash('owner123', PASSWORD_DEFAULT),
                'role' => 'owner',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'name' => 'Karyawan Test',
                'email' => 'staff@test.local',
                'password' => password_hash('staff123', PASSWORD_DEFAULT),
                'role' => 'karyawan',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'name' => 'Pelanggan Test',
                'email' => 'customer@test.local',
                'password' => password_hash('customer123', PASSWORD_DEFAULT),
                'role' => 'pelanggan',
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'name' => 'Nonaktif Test',
                'email' => 'inactive@test.local',
                'password' => password_hash('inactive123', PASSWORD_DEFAULT),
                'role' => 'karyawan',
                'is_active' => 0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->db->table('customers')->insertBatch([
            [
                'id' => 1,
                'user_id' => 3,
                'name' => 'Pelanggan Test',
                'phone' => '081200000001',
                'email' => 'customer@test.local',
                'address' => 'Alamat Test',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'user_id' => null,
                'name' => 'Pelanggan Lain',
                'phone' => '081200000002',
                'email' => null,
                'address' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $this->db->table('products')->insertBatch([
            [
                'id' => 1,
                'code' => 'T-001',
                'name' => 'Produk Stok Cukup',
                'category' => 'Cincin',
                'purity' => '22K',
                'weight' => '2.50',
                'size' => null,
                'model' => 'Test',
                'price' => 2000000,
                'stock' => 5,
                'image' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
            [
                'id' => 2,
                'code' => 'T-002',
                'name' => 'Produk Stok Sedikit',
                'category' => 'Gelang',
                'purity' => '18K',
                'weight' => '1.50',
                'size' => null,
                'model' => 'Test',
                'price' => 1000000,
                'stock' => 1,
                'image' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'deleted_at' => null,
            ],
        ]);

        $this->db->table('store_settings')->insert([
            'id' => 1,
            'store_name' => 'Toko Test',
            'email' => null,
            'phone' => null,
            'whatsapp' => null,
            'address' => null,
            'notify_status_change' => 1,
            'notify_payment' => 1,
            'notify_low_stock' => 1,
            'updated_at' => $now,
        ]);
    }
}
