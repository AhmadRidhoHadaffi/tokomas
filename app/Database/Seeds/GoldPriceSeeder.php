<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class GoldPriceSeeder extends Seeder
{
    use DemoSeedTrait;

    /**
     * The schema stores one row per purity (primary key), so only the latest
     * price is kept. Price history is intentionally not seeded.
     */
    public function run(): void
    {
        $ownerId = $this->userIdByEmail('owner@demo.test');
        $prices = [
            '24K' => 1350000,
            '22K' => 1250000,
            '18K' => 1050000,
            '17K' => 975000,
        ];

        foreach ($prices as $purity => $price) {
            $data = [
                'purity' => $purity,
                'price_per_gram' => $price,
                'updated_by' => $ownerId,
                'updated_at' => DemoPlan::datetime(),
            ];

            $existing = $this->rowBy('gold_prices', ['purity' => $purity]);

            if ($existing) {
                $this->updateRow('gold_prices', ['purity' => $purity], $data);
            } else {
                $this->db->table('gold_prices')->insert($data);
            }
        }
    }
}
