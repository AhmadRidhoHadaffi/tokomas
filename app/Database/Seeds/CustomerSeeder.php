<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        foreach (DemoPlan::customers() as $customer) {
            $userId = $customer['user_email'] ? $this->userIdByEmail($customer['user_email']) : null;
            $createdAt = DemoPlan::datetime('-' . $customer['offset'] . ' days', '09:00:00');

            $existing = $this->rowBy('customers', ['email' => $customer['email']]);
            if (! $existing) {
                $existing = $this->rowBy('customers', ['phone' => $customer['phone']]);
            }

            $data = [
                'user_id' => $userId ?: null,
                'name' => $customer['name'],
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'address' => $customer['address'],
                'updated_at' => DemoPlan::datetime(),
            ];

            if ($existing) {
                $this->updateRow('customers', ['id' => $existing['id']], $data);

                continue;
            }

            $this->insertRow('customers', $data + [
                'created_at' => $createdAt,
            ]);
        }
    }
}
