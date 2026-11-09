<?php

namespace App\Database\Seeds;

use App\Database\Seeds\Support\DemoSeedTrait;
use CodeIgniter\Database\Seeder;

class NotificationSeeder extends Seeder
{
    use DemoSeedTrait;

    public function run(): void
    {
        $pending = $this->db->table('transactions as t')
            ->select('t.invoice_no, t.customer_id, t.total, t.transaction_date, t.payment_status')
            ->join('customers as c', 'c.id = t.customer_id')
            ->like('c.email', '@demo.test', 'before')
            ->where('t.payment_status', 'Menunggu')
            ->orderBy('t.transaction_date', 'DESC')
            ->get()
            ->getResultArray();

        foreach ($pending as $transaction) {
            $createdAt = date('Y-m-d H:i:s', strtotime($transaction['transaction_date'] . ' +1 hour'));

            $this->insertNotification([
                'customer_id' => $transaction['customer_id'],
                'type' => 'Pembayaran',
                'title' => 'Menunggu Pembayaran',
                'message' => 'Invoice ' . $transaction['invoice_no'] . ' sebesar Rp ' . number_format((float) $transaction['total'], 0, ',', '.') . ' belum dibayar. Silakan selesaikan pembayaran.',
                'created_at' => $createdAt,
            ]);
        }

        $paid = $this->db->table('transactions as t')
            ->select('t.invoice_no, t.customer_id, t.total, t.transaction_date, t.payment_status')
            ->join('customers as c', 'c.id = t.customer_id')
            ->like('c.email', '@demo.test', 'before')
            ->where('t.payment_status', 'Lunas')
            ->orderBy('t.transaction_date', 'DESC')
            ->limit(20)
            ->get()
            ->getResultArray();

        foreach ($paid as $transaction) {
            $createdAt = date('Y-m-d H:i:s', strtotime($transaction['transaction_date'] . ' +2 hours'));

            $this->insertNotification([
                'customer_id' => $transaction['customer_id'],
                'type' => 'Pembayaran',
                'title' => 'Pembayaran Diterima',
                'message' => 'Pembayaran invoice ' . $transaction['invoice_no'] . ' sebesar Rp ' . number_format((float) $transaction['total'], 0, ',', '.') . ' telah kami terima. Terima kasih.',
                'created_at' => $createdAt,
            ]);
        }
    }

    private function insertNotification(array $data): void
    {
        $existing = $this->rowBy('notifications', [
            'customer_id' => $data['customer_id'],
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
        ]);

        if ($existing) {
            return;
        }

        $this->db->table('notifications')->insert([
            'customer_id' => $data['customer_id'],
            'promotion_id' => null,
            'type' => $data['type'],
            'title' => $data['title'],
            'message' => $data['message'],
            'is_read' => $this->isOldEnough($data['created_at'], 30) ? 1 : 0,
            'created_at' => $data['created_at'],
        ]);
    }
}
