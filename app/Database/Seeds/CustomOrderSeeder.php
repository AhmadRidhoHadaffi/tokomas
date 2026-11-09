<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;
use RuntimeException;

class CustomOrderSeeder extends Seeder
{
    use DemoSeedTrait;

    private const STATUSES = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];

    private const DAY_OFFSETS = [0, 1, 3, 8, 10];

    private const TIMES = ['09:30:00', '10:15:00', '11:00:00', '14:30:00', '16:00:00'];

    private const TRANSITION_MESSAGES = [
        'Diproses' => 'Pesanan Anda sedang diproses oleh tim kami.',
        'Dalam Pembuatan' => 'Pesanan Anda sedang dalam tahap pembuatan oleh pengrajin.',
        'Selesai' => 'Pesanan Anda sudah selesai. Segera ambil di toko kami.',
        'Diambil' => 'Pesanan Anda telah diambil. Terima kasih!',
    ];

    public function run(): void
    {
        $customers = DemoPlan::customers();
        $settings = $this->db->table('store_settings')->orderBy('id', 'ASC')->get(1)->getRowArray();
        $notify = ! $settings || (int) $settings['notify_status_change'] === 1;

        $staffByStatus = [
            'Masuk' => 'owner@demo.test',
            'Diproses' => 'rina@demo.test',
            'Dalam Pembuatan' => 'fajar@demo.test',
            'Selesai' => 'fajar@demo.test',
            'Diambil' => 'owner@demo.test',
        ];

        foreach (DemoPlan::customOrders() as $plan) {
            $customer = $customers[$plan['customer_index']] ?? null;
            $customerId = $customer ? $this->customerIdByEmail($customer['email']) : 0;

            if (! $customerId) {
                throw new RuntimeException('Pelanggan demo tidak ditemukan untuk ' . $plan['order_no']);
            }

            $orderDate = DemoPlan::date('-' . $plan['offset'] . ' days');
            $statusIndex = array_search($plan['status'], self::STATUSES, true);
            $finalIndex = $statusIndex === false ? 0 : $statusIndex;
            $finalDate = DemoPlan::date('-' . ($plan['offset'] - self::DAY_OFFSETS[$finalIndex]) . ' days');
            $existing = $this->rowBy('custom_orders', ['order_no' => $plan['order_no']]);

            $data = [
                'order_no' => $plan['order_no'],
                'customer_id' => $customerId,
                'jewelry_type' => $plan['jewelry_type'],
                'model' => $plan['model'],
                'engraving_name' => $plan['engraving_name'],
                'purity' => $plan['purity'],
                'weight' => $plan['weight'],
                'estimated_price' => $plan['estimated_price'],
                'dp' => $plan['dp'],
                'status' => $plan['status'],
                'order_date' => $orderDate,
                'due_date' => DemoPlan::date('-' . $plan['due_offset'] . ' days'),
                'reference_image' => $plan['image'],
                'notes' => $plan['notes'],
                'updated_at' => $finalDate . ' ' . self::TIMES[$finalIndex],
            ];

            if ($existing) {
                $orderId = (int) $existing['id'];
                $this->updateRow('custom_orders', ['id' => $orderId], $data);
            } else {
                $orderId = $this->insertRow('custom_orders', $data + [
                    'created_at' => $orderDate . ' ' . self::TIMES[0],
                ]);
            }

            $this->seedHistories($orderId, $orderDate, $finalIndex, $staffByStatus);
            $this->seedDeposit($orderId, $plan, $orderDate, $customerId);
            $this->seedSettlement($orderId, $plan, $orderDate, $finalIndex, $customerId);

            if ($notify) {
                $this->seedNotifications($orderId, $plan, $customerId, $orderDate, $finalIndex);
            }
        }
    }

    private function seedHistories(int $orderId, string $orderDate, int $finalIndex, array $staffByStatus): void
    {
        for ($i = 0; $i <= $finalIndex; $i++) {
            $status = self::STATUSES[$i];
            $createdAt = date('Y-m-d', strtotime($orderDate . ' +' . self::DAY_OFFSETS[$i] . ' days')) . ' ' . self::TIMES[$i];

            $existing = $this->rowBy('custom_order_histories', [
                'custom_order_id' => $orderId,
                'status' => $status,
            ]);

            if ($existing) {
                continue;
            }

            $this->db->table('custom_order_histories')->insert([
                'custom_order_id' => $orderId,
                'status' => $status,
                'changed_by' => $this->userIdByEmail($staffByStatus[$status]),
                'created_at' => $createdAt,
            ]);
        }
    }

    private function seedDeposit(int $orderId, array $plan, string $orderDate, int $customerId): void
    {
        if ((float) $plan['dp'] <= 0) {
            return;
        }

        $existing = $this->db->table('finances')
            ->where('custom_order_id', $orderId)
            ->where('category', 'DP Pesanan Custom')
            ->get(1)
            ->getRowArray();

        if ($existing) {
            return;
        }

        $this->insertRow('finances', [
            'user_id' => $this->userIdByEmail('owner@demo.test'),
            'transaction_id' => null,
            'custom_order_id' => $orderId,
            'type' => 'Pemasukan',
            'category' => 'DP Pesanan Custom',
            'description' => 'DP ' . $plan['order_no'] . ' - ' . $this->customerName($customerId),
            'amount' => $plan['dp'],
            'entry_date' => $orderDate,
            'created_at' => $orderDate . ' 10:00:00',
            'updated_at' => $orderDate . ' 10:00:00',
        ]);
    }

    private function seedSettlement(int $orderId, array $plan, string $orderDate, int $finalIndex, int $customerId): void
    {
        if ($plan['status'] !== 'Diambil') {
            return;
        }

        $remaining = (float) $plan['estimated_price'] - (float) $plan['dp'];

        if ($remaining <= 0) {
            return;
        }

        $existing = $this->db->table('finances')
            ->where('custom_order_id', $orderId)
            ->where('category', 'Pelunasan Custom')
            ->get(1)
            ->getRowArray();

        if ($existing) {
            return;
        }

        $settledDate = date('Y-m-d', strtotime($orderDate . ' +' . self::DAY_OFFSETS[$finalIndex] . ' days'));

        $this->insertRow('finances', [
            'user_id' => $this->userIdByEmail('owner@demo.test'),
            'transaction_id' => null,
            'custom_order_id' => $orderId,
            'type' => 'Pemasukan',
            'category' => 'Pelunasan Custom',
            'description' => 'Pelunasan ' . $plan['order_no'] . ' - ' . $this->customerName($customerId),
            'amount' => $remaining,
            'entry_date' => $settledDate,
            'created_at' => $settledDate . ' ' . self::TIMES[$finalIndex],
            'updated_at' => $settledDate . ' ' . self::TIMES[$finalIndex],
        ]);
    }

    private function seedNotifications(int $orderId, array $plan, int $customerId, string $orderDate, int $finalIndex): void
    {
        $notifications = [[
            'title' => 'Pesanan Custom Diterima',
            'message' => 'Pesanan ' . $plan['order_no'] . ' telah diterima dan akan segera diproses.',
            'created_at' => $orderDate . ' 09:35:00',
        ]];

        for ($i = 1; $i <= $finalIndex; $i++) {
            $status = self::STATUSES[$i];
            $notifications[] = [
                'title' => 'Status Pesanan Diperbarui',
                'message' => $plan['order_no'] . ' - ' . self::TRANSITION_MESSAGES[$status],
                'created_at' => date('Y-m-d', strtotime($orderDate . ' +' . self::DAY_OFFSETS[$i] . ' days')) . ' ' . self::TIMES[$i],
            ];
        }

        foreach ($notifications as $notification) {
            $existing = $this->rowBy('notifications', [
                'customer_id' => $customerId,
                'type' => 'Status Pesanan',
                'title' => $notification['title'],
                'message' => $notification['message'],
            ]);

            if ($existing) {
                continue;
            }

            $this->db->table('notifications')->insert([
                'customer_id' => $customerId,
                'promotion_id' => null,
                'type' => 'Status Pesanan',
                'title' => $notification['title'],
                'message' => $notification['message'],
                'is_read' => $this->isOldEnough($notification['created_at'], 30) ? 1 : 0,
                'created_at' => $notification['created_at'],
            ]);
        }
    }

    private function customerName(int $customerId): string
    {
        $row = $this->rowBy('customers', ['id' => $customerId]);

        return (string) ($row['name'] ?? '');
    }
}
