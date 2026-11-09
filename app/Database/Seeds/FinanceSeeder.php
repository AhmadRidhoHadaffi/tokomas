<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class FinanceSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        $this->seedSalesIncome();
        $this->seedManualEntries();
    }

    private function seedSalesIncome(): void
    {
        $transactions = $this->db->table('transactions')
            ->select('id, invoice_no, user_id, total, transaction_date')
            ->where('payment_status', 'Lunas')
            ->like('invoice_no', 'DM-', 'after')
            ->get()
            ->getResultArray();

        foreach ($transactions as $transaction) {
            $existing = $this->rowBy('finances', ['transaction_id' => $transaction['id']]);

            if ($existing) {
                continue;
            }

            $this->insertRow('finances', [
                'user_id' => $transaction['user_id'],
                'transaction_id' => $transaction['id'],
                'custom_order_id' => null,
                'type' => 'Pemasukan',
                'category' => 'Penjualan',
                'description' => 'Penjualan ' . $transaction['invoice_no'],
                'amount' => $transaction['total'],
                'entry_date' => substr($transaction['transaction_date'], 0, 10),
                'created_at' => $transaction['transaction_date'],
                'updated_at' => $transaction['transaction_date'],
            ]);
        }
    }

    private function seedManualEntries(): void
    {
        $ownerId = $this->userIdByEmail('owner@demo.test');

        foreach (DemoPlan::manualFinances() as $entry) {
            $existing = $this->rowBy('finances', ['description' => $entry['description']]);

            $data = [
                'user_id' => $ownerId,
                'transaction_id' => null,
                'custom_order_id' => null,
                'type' => $entry['type'],
                'category' => $entry['category'],
                'description' => $entry['description'],
                'amount' => $entry['amount'],
                'entry_date' => $entry['date'],
                'updated_at' => DemoPlan::datetime(),
            ];

            if ($existing) {
                $this->updateRow('finances', ['id' => $existing['id']], $data);

                continue;
            }

            $this->insertRow('finances', $data + [
                'created_at' => $entry['date'] . ' 18:00:00',
            ]);
        }
    }
}
