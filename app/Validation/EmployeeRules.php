<?php

namespace App\Validation;

class EmployeeRules
{
    public static function create(): array
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
            'email' => [
                'rules' => 'required|valid_email|max_length[100]|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter',
                    'is_unique' => 'Email sudah digunakan',
                ],
            ],
            'password' => [
                'rules' => 'required|min_length[8]|max_length[100]',
                'errors' => [
                    'required' => 'Kata sandi wajib diisi',
                    'min_length' => 'Kata sandi minimal 8 karakter',
                    'max_length' => 'Kata sandi maksimal 100 karakter',
                ],
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi wajib diisi',
                    'matches' => 'Konfirmasi kata sandi tidak sama',
                ],
            ],
            'is_active' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => 'Status tidak valid',
                ],
            ],
        ];
    }

    public static function update(int $id): array
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
            'email' => [
                'rules' => 'required|valid_email|max_length[100]|is_unique[users.email,id,' . $id . ']',
                'errors' => [
                    'required' => 'Email wajib diisi',
                    'valid_email' => 'Email tidak valid',
                    'max_length' => 'Email maksimal 100 karakter',
                    'is_unique' => 'Email sudah digunakan',
                ],
            ],
            'is_active' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => [
                    'in_list' => 'Status tidak valid',
                ],
            ],
        ];
    }

    public static function password(): array
    {
        return [
            'password' => [
                'rules' => 'required|min_length[8]|max_length[100]',
                'errors' => [
                    'required' => 'Kata sandi wajib diisi',
                    'min_length' => 'Kata sandi minimal 8 karakter',
                    'max_length' => 'Kata sandi maksimal 100 karakter',
                ],
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi kata sandi wajib diisi',
                    'matches' => 'Konfirmasi kata sandi tidak sama',
                ],
            ],
        ];
    }
}
