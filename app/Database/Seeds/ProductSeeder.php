<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        $createdAt = DemoPlan::datetime('-365 days', '08:00:00');

        foreach (DemoPlan::products() as $product) {
            $existing = $this->rowBy('products', ['code' => $product['code']]);

            $data = [
                'code' => $product['code'],
                'name' => $product['name'],
                'category' => $product['category'],
                'purity' => $product['purity'],
                'weight' => $product['weight'],
                'size' => $product['size'],
                'model' => $product['model'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'image' => $product['image'] ?? null,
                'deleted_at' => ! empty($product['deleted'])
                    ? DemoPlan::datetime('-30 days', '12:00:00')
                    : null,
                'updated_at' => DemoPlan::datetime(),
            ];

            if ($existing) {
                $this->updateRow('products', ['id' => $existing['id']], $data);

                continue;
            }

            $this->insertRow('products', $data + ['created_at' => $createdAt]);
        }
    }
}
