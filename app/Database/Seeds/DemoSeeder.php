<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(SettingSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(GoldPriceSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(FinanceSeeder::class);
        $this->call(CustomOrderSeeder::class);
        $this->call(PromotionSeeder::class);
        $this->call(NotificationSeeder::class);

        $this->report();
    }

    private function report(): void
    {
        $counts = [
            'users (@demo.test)' => $this->db->table('users')->like('email', '@demo.test', 'before')->countAllResults(),
            'customers (@demo.test)' => $this->db->table('customers')->like('email', '@demo.test', 'before')->countAllResults(),
            'products (DM-*)' => $this->db->table('products')->like('code', 'DM-', 'after')->countAllResults(),
            'transactions (DM-*)' => $this->db->table('transactions')->like('invoice_no', 'DM-', 'after')->countAllResults(),
            'custom orders (PD-2026-*)' => $this->db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')->countAllResults(),
            'promotions ([DEMO])' => $this->db->table('promotions')->like('title', '[DEMO]', 'after')->countAllResults(),
            'notifications (demo customers)' => $this->db->table('notifications as n')
                ->join('customers as c', 'c.id = n.customer_id')
                ->like('c.email', '@demo.test', 'before')
                ->countAllResults(),
            'finances ([DEMO] + linked)' => $this->db->table('finances')->like('description', '[DEMO]', 'after')->countAllResults(),
            'transaction details (demo)' => $this->db->table('transaction_details as td')
                ->join('transactions as t', 't.id = td.transaction_id')
                ->like('t.invoice_no', 'DM-', 'after')
                ->countAllResults(),
            'custom order histories (demo)' => $this->db->table('custom_order_histories as h')
                ->join('custom_orders as o', 'o.id = h.custom_order_id')
                ->like('o.order_no', 'PD-2026-', 'after')
                ->countAllResults(),
            'store settings' => $this->db->table('store_settings')->countAllResults(),
            'gold prices' => $this->db->table('gold_prices')->countAllResults(),
            'promotion customers (demo)' => $this->db->table('promotion_customers as pc')
                ->join('promotions as p', 'p.id = pc.promotion_id')
                ->like('p.title', '[DEMO]', 'after')
                ->countAllResults(),
        ];

        echo "Demo data seeded (idempotent).\n";

        foreach ($counts as $label => $count) {
            echo sprintf("  %-32s %d\n", $label, $count);
        }
    }
}
