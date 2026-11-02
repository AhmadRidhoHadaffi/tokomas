<?php

namespace App\Services;

use App\Models\UserModel;

class EmployeeService
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->userModel->builder();
        $builder->where('role', 'karyawan');

        if (!empty($filters['search'])) {
            $builder->groupStart()->like('name', $filters['search'])->orLike('email', $filters['search'])->groupEnd();
        }

        if (isset($filters['is_active'])) {
            $builder->where('is_active', (int)$filters['is_active']);
        }

        $builder->orderBy('created_at', 'DESC');

        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $employees = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Remove password
        foreach ($employees as &$emp) {
            unset($emp['password']);
        }

        return ['items' => $employees, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'total_pages' => ceil($total / $perPage)];
    }

    public function getById(int $id): ?array
    {
        $emp = $this->userModel->find($id);
        if ($emp && $emp['role'] === 'karyawan') {
            unset($emp['password']);
            return $emp;
        }
        return null;
    }

    public function create(array $data): int
    {
        $data['role'] = 'karyawan';
        $data['is_active'] = $data['is_active'] ?? 1;
        return $this->userModel->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        unset($data['role'], $data['password']);
        return $this->userModel->update($id, $data);
    }

    public function toggleActive(int $id): bool
    {
        $emp = $this->userModel->find($id);
        if (!$emp || $emp['role'] !== 'karyawan') return false;
        return $this->userModel->update($id, ['is_active' => $emp['is_active'] ? 0 : 1]);
    }

    public function resetPassword(int $id, string $newPassword): bool
    {
        $emp = $this->userModel->find($id);
        if (!$emp || $emp['role'] !== 'karyawan') return false;
        return $this->userModel->update($id, ['password' => $newPassword]);
    }
}
