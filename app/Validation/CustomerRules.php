<?php

namespace App\Validation;

class CustomerRules
{
    public static function update(int $id): array
    {
        return [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama pelanggan wajib diisi',
                    'min_length' => 'Nama minimal 3 karakter',
                    'max_length' => 'Nama maksimal 100 karakter',
                ],
            ],
            'phone' => [
                'rules' => 'required|min_length[10]|max_length[15]|is_unique[customers.phone,id,' . $id . ']',
                'errors' => [
                    'required' => 'Nomor HP wajib diisi',
                    'min_length' => 'Nomor HP minimal 10 digit',
                    'max_length' => 'Nomor HP maksimal 15 digit',
                    'is_unique' => 'Nomor HP sudah digunakan',
                ],
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email|max_length[100]|is_unique[customers.email,id,' . $id . ']',
                'errors' => [
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
}
