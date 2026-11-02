<?php

namespace App\Services;

use App\Models\CustomerModel;

class CustomerService
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->customerModel->builder();

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('name', $filters['search'])
                ->orLike('phone', $filters['search'])
                ->orLike('email', $filters['search'])
                ->groupEnd();
        }

        // Sorting
        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        // Pagination
        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $customers = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Add stats for each customer
        foreach ($customers as &$customer) {
            $stats = $this->customerModel->getWithStats($customer['id']);
            $customer['total_transactions'] = $stats['total_transactions'] ?? 0;
            $customer['total_spent'] = $stats['total_spent'] ?? 0;
        }

        return [
            'items' => $customers,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?array
    {
        return $this->customerModel->getWithStats($id);
    }

    public function getByUserId(int $userId): ?array
    {
        return $this->customerModel->findByUserId($userId);
    }

    public function update(int $id, array $data): bool
    {
        // Don't allow changing user_id
        unset($data['user_id']);
        return $this->customerModel->update($id, $data);
    }
}

