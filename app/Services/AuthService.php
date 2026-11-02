<?php

namespace App\Services;

use App\Models\UserModel;
use App\Models\CustomerModel;
use App\Libraries\JwtLibrary;

class AuthService
{
    protected UserModel $userModel;
    protected CustomerModel $customerModel;
    protected JwtLibrary $jwt;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->customerModel = new CustomerModel();
        $this->jwt = new JwtLibrary();
    }

    public function register(array $data): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Create user
            $userId = $this->userModel->insert([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'pelanggan',
                'is_active' => 1,
            ]);

            if (!$userId) {
                throw new \Exception('Gagal membuat user');
            }

            // Create customer
            $customerId = $this->customerModel->insert([
                'user_id' => $userId,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'] ?? null,
            ]);

            if (!$customerId) {
                throw new \Exception('Gagal membuat customer');
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi gagal');
            }

            $user = $this->userModel->find($userId);
            $token = $this->jwt->encode([
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ]);

            return [
                'user' => [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                ],
                'token' => $token,
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userModel->findActiveByEmail($email);

        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        $token = $this->jwt->encode([
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ]);

        return [
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ],
            'token' => $token,
        ];
    }

    public function changePassword(int $userId, string $currentPassword, string $newPassword): bool
    {
        $user = $this->userModel->find($userId);

        if (!$user) {
            return false;
        }

        if (!password_verify($currentPassword, $user['password'])) {
            return false;
        }

        return $this->userModel->update($userId, ['password' => $newPassword]);
    }
}

