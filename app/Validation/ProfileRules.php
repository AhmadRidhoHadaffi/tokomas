<?php

namespace App\Validation;

class ProfileRules
{
    public static function update(int $customerId, int $userId): array
    {
        return [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter',
                ],
            ],
            'phone' => [
                'rules' => 'required|min_length[10]|max_length[15]|is_unique[customers.phone,id,' . $customerId . ']',
                'errors' => [
                    'required' => 'Nomor HP wajib diisi',
                    'min_length' => 'Nomor HP minimal 10 digit',
                    'max_length' => 'Nomor HP maksimal 15 digit',
                    'is_unique' => 'Nomor HP sudah digunakan',
                ],
            ],
            'email' => [
                'rules' => 'required|valid_email|max_length[100]'
                    . '|is_unique[customers.email,id,' . $customerId . ']'
                    . '|is_unique[users.email,id,' . $userId . ']',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter',
                    'is_unique' => 'Email sudah digunakan',
                ],
            ],
            'address' => [
                'rules' => 'permit_empty|max_length[200]',
                'errors' => [
                    'max_length' => 'Alamat maksimal 200 karakter',
                ],
            ],
        ];
    }

    public static function password(): array
    {
        return [
            'current_password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kata sandi saat ini wajib diisi',
                ],
            ],
            'new_password' => [
                'rules' => 'required|min_length[8]|max_length[100]',
                'errors' => [
                    'required' => 'Kata sandi baru wajib diisi',
                    'min_length' => 'Kata sandi baru minimal 8 karakter',
                    'max_length' => 'Kata sandi baru maksimal 100 karakter',
                ],
            ],
            'new_password_confirm' => [
                'rules' => 'required|matches[new_password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi baru wajib diisi',
                    'matches' => 'Konfirmasi kata sandi baru tidak sama',
                ],
            ],
        ];
    }
}
