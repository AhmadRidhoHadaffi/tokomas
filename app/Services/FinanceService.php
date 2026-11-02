<?php

namespace App\Services;

use App\Models\FinanceModel;
use App\Models\UserModel;

class FinanceService
{
    protected FinanceModel $financeModel;
    protected UserModel $userModel;

    protected array $categories = [
        'Pemasukan' => ['Penjualan', 'DP Pesanan Custom', 'Pelunasan Custom', 'Beli Emas', 'Lainnya'],
        'Pengeluaran' => ['Bahan Baku', 'Upah Pengrajin', 'Operasional', 'Sewa', 'Gaji Karyawan', 'Lainnya'],
    ];

    public function __construct()
    {
        $this->financeModel = new FinanceModel();
        $this->userModel = new UserModel();
    }

    public function validateCategory(string $type, string $category): bool
    {
        return in_array($category, $this->categories[$type] ?? []);
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->financeModel->builder();
        $builder->select('finances.*, users.name as recorded_by_name')
            ->join('users', 'users.id = finances.user_id');

        // Filter by type
        if (!empty($filters['type'])) {
            $builder->where('finances.type', $filters['type']);
        }

        // Filter by category
        if (!empty($filters['category'])) {
            $builder->where('finances.category', $filters['category']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $builder->where('finances.entry_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('finances.entry_date <=', $filters['end_date']);
        }

        // Search
        if (!empty($filters['search'])) {
            $builder->like('finances.description', $filters['search']);
        }

        // Sorting
        $sortField = $filters['sort'] ?? 'finances.entry_date';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        // Pagination
        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $finances = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'items' => $finances,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?array
    {
        $finance = $this->financeModel->find($id);
        if (!$finance) {
            return null;
        }

        $user = $this->userModel->find($finance['user_id']);
        $finance['recorded_by_name'] = $user['name'] ?? '';

        return $finance;
    }

    public function create(array $data, int $userId): int
    {
        if (!$this->validateCategory($data['type'], $data['category'])) {
            throw new \Exception('Kategori tidak sesuai dengan tipe');
        }

        $data['user_id'] = $userId;
        $data['entry_date'] = $data['entry_date'] ?? date('Y-m-d');

        return $this->financeModel->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['type']) && isset($data['category'])) {
            if (!$this->validateCategory($data['type'], $data['category'])) {
                throw new \Exception('Kategori tidak sesuai dengan tipe');
            }
        }

        // Don't allow updating auto-generated records
        $finance = $this->financeModel->find($id);
        if ($finance && ($finance['transaction_id'] || $finance['custom_order_id'])) {
            throw new \Exception('Tidak dapat mengubah catatan otomatis dari transaksi');
        }

        return $this->financeModel->update($id, $data);
    }

    public function delete(int $id): bool
    {
        // Don't allow deleting auto-generated records
        $finance = $this->financeModel->find($id);
        if ($finance && ($finance['transaction_id'] || $finance['custom_order_id'])) {
            throw new \Exception('Tidak dapat menghapus catatan otomatis dari transaksi');
        }

        return $this->financeModel->delete($id);
    }

    public function getSummary(string $startDate, string $endDate): array
    {
        return $this->financeModel->getSummary($startDate, $endDate);
    }

    public function getCategories(): array
    {
        return $this->categories;
    }
}
