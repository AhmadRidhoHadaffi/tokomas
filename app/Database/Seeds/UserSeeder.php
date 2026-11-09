<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        foreach (DemoPlan::users() as $user) {
            $createdAt = DemoPlan::datetime('-' . $user['offset'] . ' days', '08:00:00');
            $existing = $this->rowBy('users', ['email' => $user['email']]);

            if (! $existing) {
                $this->insertRow('users', [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => password_hash(DemoPlan::PASSWORD, PASSWORD_DEFAULT),
                    'role' => $user['role'],
                    'is_active' => $user['is_active'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                continue;
            }

            $data = [
                'name' => $user['name'],
                'role' => $user['role'],
                'is_active' => $user['is_active'],
                'updated_at' => DemoPlan::datetime(),
            ];

            if (! password_verify(DemoPlan::PASSWORD, $existing['password'])) {
                $data['password'] = password_hash(DemoPlan::PASSWORD, PASSWORD_DEFAULT);
            }

            $this->updateRow('users', ['id' => $existing['id']], $data);
        }
    }
}
