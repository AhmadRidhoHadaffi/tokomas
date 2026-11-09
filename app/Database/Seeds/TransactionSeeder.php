<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoPlan;
use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;
use RuntimeException;

class TransactionSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        $customers = DemoPlan::customers();

        foreach (DemoPlan::transactions() as $transaction) {
            if ($this->rowBy('transactions', ['invoice_no' => $transaction['invoice_no']])) {
                continue;
            }

            $customer = $customers[$transaction['customer_index']] ?? null;
            $customerId = $customer ? $this->customerIdByEmail($customer['email']) : 0;
            $cashierId = $this->userIdByEmail($transaction['cashier_email']);

            if (! $customerId || ! $cashierId) {
                throw new RuntimeException('Relasi demo transaksi tidak lengkap: ' . $transaction['invoice_no']);
            }

            $dateTime = $transaction['date'] . ' ' . $transaction['time'];
            $transactionId = $this->insertRow('transactions', [
                'invoice_no' => $transaction['invoice_no'],
                'customer_id' => $customerId,
                'user_id' => $cashierId,
                'transaction_date' => $dateTime,
                'discount' => $transaction['discount'],
                'total' => $transaction['total'],
                'payment_method' => $transaction['payment_method'],
                'payment_status' => $transaction['payment_status'],
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ]);

            foreach ($transaction['items'] as $item) {
                $productId = $this->productIdByCode($item['code']);

                if (! $productId) {
                    throw new RuntimeException('Produk demo tidak ditemukan: ' . $item['code']);
                }

                $this->db->table('transaction_details')->insert([
                    'transaction_id' => $transactionId,
                    'product_id' => $productId,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }
        }
    }
}
