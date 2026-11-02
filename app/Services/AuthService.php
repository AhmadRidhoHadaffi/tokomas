<?php

namespace App\Services;

use App\Models\CustomerModel;
use App\Models\UserModel;

class AuthService
{
    protected UserModel $userModel;
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->customerModel = new CustomerModel();
    }

    public function register(array $data): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $userId = $this->userModel->insert([
                'name' => $data['name'], 'email' => $data['email'], 'password' => $data['password'],
                'role' => 'pelanggan', 'is_active' => 1,
            ]);
            if (! $userId) {
                throw new \RuntimeException('Gagal membuat user.');
            }

            $customerId = $this->customerModel->insert([
                'user_id' => $userId, 'name' => $data['name'], 'phone' => $data['phone'],
                'email' => $data['email'], 'address' => $data['address'] ?? null,
            ]);
            if (! $customerId) {
                throw new \RuntimeException('Gagal membuat data pelanggan.');
            }

            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Registrasi gagal.');
            }

            return ['user' => $this->publicUser($this->userModel->find($userId))];
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userModel->findActiveByEmail($email);
        if (! $user || ! password_verify($password, $user['password'])) {
            return null;
        }
        return ['user' => $this->publicUser($user)];
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->userModel->find($userId);
        if (! $user || ! password_verify($currentPassword, $user['password'])) {
            return false;
        }
        return $this->userModel->update($userId, ['password' => $newPassword]);
    }

    private function publicUser(?array $user): array
    {
        if (! $user) {
            return [];
        }
        return ['id' => (int) $user['id'], 'name' => $user['name'], 'email' => $user['email'], 'role' => $user['role']];
    }
}
