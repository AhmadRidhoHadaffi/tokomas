<?php

namespace App\Validation;

class PromotionRules
{
    public static function create(): array
    {
        return [
            'title' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Judul promosi wajib diisi',
                    'max_length' => 'Judul maksimal 100 karakter',
                ],
            ],
            'message' => [
                'rules' => 'required|max_length[300]',
                'errors' => [
                    'required' => 'Isi pesan wajib diisi',
                    'max_length' => 'Isi pesan maksimal 300 karakter',
                ],
            ],
            'target_type' => [
                'rules' => 'required|in_list[semua,terpilih]',
                'errors' => [
                    'required' => 'Target promosi wajib dipilih',
                    'in_list' => 'Target promosi tidak valid',
                ],
            ],
        ];
    }
}
