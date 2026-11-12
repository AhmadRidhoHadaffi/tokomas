<?php

namespace App\Validation;

class StoreSettingRules
{
    public static function update(): array
    {
        return [
            'store_name' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Nama toko wajib diisi',
                    'max_length' => 'Nama toko maksimal 100 karakter',
                ],
            ],
            'email' => [
                'rules' => 'permit_empty|valid_email|max_length[100]',
                'errors' => [
                    'valid_email' => 'Email toko tidak valid',
                    'max_length' => 'Email maksimal 100 karakter',
                ],
            ],
            'phone' => [
                'rules' => 'permit_empty|max_length[15]',
                'errors' => [
                    'max_length' => 'Telepon maksimal 15 karakter',
                ],
            ],
            'whatsapp' => [
                'rules' => 'permit_empty|max_length[15]',
                'errors' => [
                    'max_length' => 'Nomor WhatsApp maksimal 15 karakter',
                ],
            ],
            'address' => [
                'rules' => 'permit_empty|max_length[200]',
                'errors' => [
                    'max_length' => 'Alamat maksimal 200 karakter',
                ],
            ],
            'notify_status_change' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => ['in_list' => 'Nilai notifikasi tidak valid'],
            ],
            'notify_payment' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => ['in_list' => 'Nilai notifikasi tidak valid'],
            ],
            'notify_low_stock' => [
                'rules' => 'permit_empty|in_list[0,1]',
                'errors' => ['in_list' => 'Nilai notifikasi tidak valid'],
            ],
        ];
    }
}
