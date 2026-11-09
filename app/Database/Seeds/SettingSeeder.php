<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class SettingSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        $data = [
            'store_name' => 'Toko Mas Gembira',
            'email' => 'info@tokomasgembira.id',
            'phone' => '0751-123456',
            'whatsapp' => '6281234567890',
            'address' => 'Pasar Raya Padang, Blok A No. 12',
            'notify_status_change' => 1,
            'notify_payment' => 1,
            'notify_low_stock' => 1,
            'updated_at' => DemoPlan::datetime(),
        ];

        $settings = $this->db->table('store_settings')->orderBy('id', 'ASC')->get(1)->getRowArray();

        if ($settings) {
            $this->updateRow('store_settings', ['id' => $settings['id']], $data);

            return;
        }

        $this->insertRow('store_settings', $data);
    }
}
