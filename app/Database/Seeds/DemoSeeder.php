<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run()
    {
        // Users
        $this->db->table('users')->insert([
            'name' => 'Hj. Rosmaniar',
            'email' => 'owner@tokomasgembira.id',
            'password' => password_hash('owner123', PASSWORD_DEFAULT),
            'role' => 'owner',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('users')->insert([
            'name' => 'Rina Marlina',
            'email' => 'rina@tokomasgembira.id',
            'password' => password_hash('karyawan123', PASSWORD_DEFAULT),
            'role' => 'karyawan',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $userId = $this->db->table('users')->insert([
            'name' => 'Siti Rahmawati',
            'email' => 'siti@example.com',
            'password' => password_hash('pelanggan123', PASSWORD_DEFAULT),
            'role' => 'pelanggan',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Customer
        $this->db->table('customers')->insert([
            'user_id' => $userId,
            'name' => 'Siti Rahmawati',
            'phone' => '081277881020',
            'email' => 'siti@example.com',
            'address' => 'Jl. Sudirman No. 10, Padang',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Products
        $products = [
            ['code' => 'CE-001', 'name' => 'Cincin Filigree Minang', 'category' => 'Cincin', 'purity' => '22K', 'weight' => 3.2, 'size' => '14', 'model' => 'Filigree', 'price' => 5280000, 'stock' => 5],
            ['code' => 'KL-001', 'name' => 'Kalung Rumah Gadang', 'category' => 'Kalung', 'purity' => '24K', 'weight' => 8.5, 'size' => null, 'model' => 'Rumah Gadang', 'price' => 14450000, 'stock' => 2],
            ['code' => 'GL-001', 'name' => 'Gelang Bunga Cengkeh', 'category' => 'Gelang', 'purity' => '18K', 'weight' => 12.0, 'size' => null, 'model' => 'Bunga', 'price' => 10800000, 'stock' => 3],
            ['code' => 'AN-001', 'name' => 'Anting Perak Pauh Kacang', 'category' => 'Anting', 'purity' => 'Perak 925', 'weight' => 2.5, 'size' => null, 'model' => 'Pauh Kacang', 'price' => 450000, 'stock' => 10],
            ['code' => 'LI-001', 'name' => 'Liontin Tanduk Kerbau', 'category' => 'Liontin', 'purity' => '17K', 'weight' => 5.5, 'size' => null, 'model' => 'Tanduk', 'price' => 4125000, 'stock' => 0],
        ];

        foreach ($products as $p) {
            $p['created_at'] = date('Y-m-d H:i:s');
            $p['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('products')->insert($p);
        }

        // Store Settings
        $this->db->table('store_settings')->insert([
            'store_name' => 'Toko Mas Gembira',
            'email' => 'info@tokomasgembira.id',
            'phone' => '0751-123456',
            'whatsapp' => '6281234567890',
            'address' => 'Pasar Raya Padang, Blok A No. 12',
            'notify_status_change' => 1,
            'notify_payment' => 1,
            'notify_low_stock' => 1,
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        // Gold Prices
        $goldPrices = [
            ['purity' => '24K', 'price_per_gram' => 1350000, 'updated_by' => 1],
            ['purity' => '22K', 'price_per_gram' => 1250000, 'updated_by' => 1],
            ['purity' => '18K', 'price_per_gram' => 1050000, 'updated_by' => 1],
            ['purity' => '17K', 'price_per_gram' => 975000, 'updated_by' => 1],
        ];

        foreach ($goldPrices as $gp) {
            $gp['updated_at'] = date('Y-m-d H:i:s');
            $this->db->table('gold_prices')->insert($gp);
        }

        echo "Demo data seeded successfully.\n";
    }
}
