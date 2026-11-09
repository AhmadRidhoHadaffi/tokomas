<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DemoCleanSeeder extends Seeder
{
    public function run(): void
    {
        $customerIds = $this->ids(
            $this->db->table('customers')
                ->groupStart()
                ->like('email', '@demo.test', 'before')
                ->orLike('phone', '08137788', 'after')
                ->groupEnd()
        );
        $transactionIds = $this->ids(
            $this->db->table('transactions')->like('invoice_no', 'DM-', 'after')
        );
        $orderIds = $this->ids(
            $this->db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')
        );
        $promotionIds = $this->ids(
            $this->db->table('promotions')->like('title', '[DEMO]', 'after')
        );

        $this->deleteWhereIn('notifications', 'customer_id', $customerIds);
        $this->deleteWhereIn('notifications', 'promotion_id', $promotionIds);
        $this->deleteWhereIn('finances', 'transaction_id', $transactionIds);
        $this->deleteWhereIn('finances', 'custom_order_id', $orderIds);
        $this->db->table('finances')->like('description', '[DEMO]', 'after')->delete();
        $this->deleteWhereIn('transaction_details', 'transaction_id', $transactionIds);
        $this->deleteWhereIn('transactions', 'id', $transactionIds);
        $this->deleteWhereIn('custom_order_histories', 'custom_order_id', $orderIds);
        $this->deleteWhereIn('custom_orders', 'id', $orderIds);
        $this->deleteWhereIn('promotion_customers', 'promotion_id', $promotionIds);
        $this->deleteWhereIn('promotions', 'id', $promotionIds);
        $this->deleteWhereIn('customers', 'id', $customerIds);
        $this->db->table('users')->like('email', '@demo.test', 'before')->delete();
        $this->db->table('products')->like('code', 'DM-', 'after')->delete();

        echo "Demo data removed. Legacy/non-demo rows were left untouched.\n";
    }

    /**
     * @return int[]
     */
    private function ids(object $builder): array
    {
        return array_map(
            static fn (array $row): int => (int) $row['id'],
            $builder->select('id')->get()->getResultArray()
        );
    }

    /**
     * @param int[] $ids
     */
    private function deleteWhereIn(string $table, string $column, array $ids): void
    {
        if ($ids === []) {
            return;
        }

        $this->db->table($table)->whereIn($column, $ids)->delete();
    }
}
