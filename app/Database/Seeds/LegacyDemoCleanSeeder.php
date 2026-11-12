<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Remove the legacy demo dataset that shipped before the current demo fixture.
 *
 * Targets only the old seed fingerprint (`@tokomasgembira.id` domain and the
 * original `siti@example.com` customer login). Rows belonging to the current
 * `@demo.test` fixture are never touched, so this is safe to re-run.
 */
class LegacyDemoCleanSeeder extends Seeder
{
    private const LEGACY_DOMAINS = ['@tokomasgembira.id'];

    private const LEGACY_EMAILS = ['siti@example.com'];

    public function run(): void
    {
        $userIds = $this->campaignUserIds();
        $promotionIds = $this->ids(
            $this->db->table('promotions')->whereIn('user_id', $userIds ?: [0])
        );
        $customerIds = $this->legacyCustomerIds($userIds);

        $transactionIds = $this->ids(
            $this->db->table('transactions')->whereIn('customer_id', $customerIds ?: [0])
        );
        $orderIds = $this->ids(
            $this->db->table('custom_orders')->whereIn('customer_id', $customerIds ?: [0])
        );

        // Dependent rows first.
        $this->deleteWhereIn('notifications', 'customer_id', $customerIds);
        $this->deleteWhereIn('notifications', 'promotion_id', $promotionIds);
        $this->deleteWhereIn('promotion_customers', 'customer_id', $customerIds);
        $this->deleteWhereIn('promotion_customers', 'promotion_id', $promotionIds);
        $this->deleteWhereIn('finances', 'transaction_id', $transactionIds);
        $this->deleteWhereIn('finances', 'custom_order_id', $orderIds);
        $this->deleteWhereIn('transaction_details', 'transaction_id', $transactionIds);
        $this->deleteWhereIn('custom_order_histories', 'custom_order_id', $orderIds);
        $this->deleteWhereIn('transactions', 'id', $transactionIds);
        $this->deleteWhereIn('custom_orders', 'id', $orderIds);
        $this->deleteWhereIn('promotions', 'id', $promotionIds);

        // Keep business history that still references the old accounts.
        $this->reassignUserReferences($userIds);

        $this->deleteWhereIn('customers', 'id', $customerIds);
        $this->deleteWhereIn('users', 'id', $userIds);

        echo 'Legacy demo removed: ' . count($userIds) . ' user(s), '
            . count($customerIds) . ' customer(s), '
            . count($transactionIds) . ' transaction(s), '
            . count($orderIds) . " order(s).\n";
    }

    /**
     * @return int[]
     */
    private function campaignUserIds(): array
    {
        $builder = $this->db->table('users');
        $builder->groupStart();

        foreach (self::LEGACY_DOMAINS as $domain) {
            $builder->orLike('email', $domain, 'before');
        }

        $builder->orWhereIn('email', self::LEGACY_EMAILS);
        $builder->groupEnd();

        return $this->ids($builder);
    }

    /**
     * @param int[] $userIds
     *
     * @return int[]
     */
    private function legacyCustomerIds(array $userIds): array
    {
        $builder = $this->db->table('customers');
        $builder->groupStart();
        $builder->whereIn('user_id', $userIds ?: [0]);

        foreach (self::LEGACY_DOMAINS as $domain) {
            $builder->orLike('email', $domain, 'before');
        }

        $builder->orWhereIn('email', self::LEGACY_EMAILS);
        $builder->groupEnd();

        return $this->ids($builder);
    }

    /**
     * Re-point NOT NULL user references so legacy accounts can be removed
     * without orphaning legitimate records.
     *
     * @param int[] $userIds
     */
    private function reassignUserReferences(array $userIds): void
    {
        if ($userIds === []) {
            return;
        }

        $replacement = $this->db->table('users')
            ->select('id')
            ->where('role', 'owner')
            ->whereNotIn('id', $userIds)
            ->orderBy('id', 'ASC')
            ->get(1)
            ->getRowArray();

        if (!$replacement) {
            return;
        }

        $replacementId = (int) $replacement['id'];

        foreach ([
            'transactions' => 'user_id',
            'finances' => 'user_id',
            'custom_order_histories' => 'changed_by',
            'promotions' => 'user_id',
            'gold_prices' => 'updated_by',
        ] as $table => $column) {
            $this->db->table($table)->whereIn($column, $userIds)->update([$column => $replacementId]);
        }
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
