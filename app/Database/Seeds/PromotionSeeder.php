<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class PromotionSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        $customers = DemoPlan::customers();
        $ownerId = $this->userIdByEmail('owner@demo.test');

        foreach (DemoPlan::promotions() as $plan) {
            $sendAt = $plan['send_offset'] === null
                ? null
                : DemoPlan::datetime(($plan['send_offset'] >= 0 ? '+' : '') . $plan['send_offset'] . ' days', '09:00:00');

            $existing = $this->rowBy('promotions', ['title' => $plan['title']]);

            $data = [
                'user_id' => $ownerId,
                'title' => $plan['title'],
                'message' => $plan['message'],
                'target_type' => $plan['target_type'],
                'status' => $plan['status'],
                'send_at' => $sendAt,
                'updated_at' => DemoPlan::datetime(),
            ];

            if ($existing) {
                $promotionId = (int) $existing['id'];
                $this->updateRow('promotions', ['id' => $promotionId], $data);
            } else {
                $promotionId = $this->insertRow('promotions', $data + [
                    'created_at' => DemoPlan::datetime('-45 days', '08:00:00'),
                ]);
            }

            $targetIds = [];

            if ($plan['target_type'] === 'terpilih') {
                foreach ($plan['targets'] as $index) {
                    $customer = $customers[$index] ?? null;
                    if ($customer === null) {
                        continue;
                    }

                    $customerId = $this->customerIdByEmail($customer['email']);
                    if (! $customerId) {
                        continue;
                    }

                    $targetIds[] = $customerId;

                    if (! $this->rowBy('promotion_customers', [
                        'promotion_id' => $promotionId,
                        'customer_id' => $customerId,
                    ])) {
                        $this->db->table('promotion_customers')->insert([
                            'promotion_id' => $promotionId,
                            'customer_id' => $customerId,
                        ]);
                    }
                }
            }

            if ($plan['status'] !== 'Terkirim') {
                continue;
            }

            if ($plan['target_type'] === 'semua') {
                $rows = $this->db->table('customers')->select('id')->get()->getResultArray();
                $targetIds = array_map(static fn (array $row): int => (int) $row['id'], $rows);
            }

            foreach ($targetIds as $customerId) {
                $existingNotification = $this->rowBy('notifications', [
                    'customer_id' => $customerId,
                    'promotion_id' => $promotionId,
                    'type' => 'Promosi',
                    'title' => $plan['title'],
                ]);

                if ($existingNotification) {
                    continue;
                }

                $this->db->table('notifications')->insert([
                    'customer_id' => $customerId,
                    'promotion_id' => $promotionId,
                    'type' => 'Promosi',
                    'title' => $plan['title'],
                    'message' => $plan['message'],
                    'is_read' => $this->isOldEnough($sendAt, 30) ? 1 : 0,
                    'created_at' => $sendAt,
                ]);
            }
        }
    }
}
