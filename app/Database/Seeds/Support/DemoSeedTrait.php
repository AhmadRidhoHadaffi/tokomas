<?php

namespace App\Database\Seeds\Support;

/**
 * Helpers shared by the modular demo seeders.
 *
 * The seeders use the query builder directly instead of the models so that
 * created_at/updated_at can be set to deterministic demo dates. All lookups
 * use natural keys, which keeps the seeders idempotent.
 */
trait DemoSeedTrait
{
    protected function rowBy(string $table, array $where): ?array
    {
        $row = $this->db->table($table)->where($where)->get(1)->getRowArray();

        return $row ?: null;
    }

    protected function insertRow(string $table, array $data): int
    {
        $this->db->table($table)->insert($data);

        return (int) $this->db->insertID();
    }

    protected function updateRow(string $table, array $where, array $data): void
    {
        if ($data !== []) {
            $this->db->table($table)->where($where)->update($data);
        }
    }

    protected function userIdByEmail(string $email): int
    {
        $row = $this->rowBy('users', ['email' => $email]);

        return (int) ($row['id'] ?? 0);
    }

    protected function customerIdByEmail(string $email): int
    {
        $row = $this->rowBy('customers', ['email' => $email]);

        return (int) ($row['id'] ?? 0);
    }

    protected function productIdByCode(string $code): int
    {
        $row = $this->rowBy('products', ['code' => $code]);

        return (int) ($row['id'] ?? 0);
    }

    protected function idsOf(string $table, array $where): array
    {
        return array_map(
            static fn (array $row): int => (int) $row['id'],
            $this->db->table($table)->select('id')->where($where)->get()->getResultArray()
        );
    }

    protected function isOldEnough(string $datetime, int $days = 30): bool
    {
        return strtotime($datetime) < strtotime(DemoPlan::ANCHOR . ' 23:59:59 -' . $days . ' days');
    }
}
