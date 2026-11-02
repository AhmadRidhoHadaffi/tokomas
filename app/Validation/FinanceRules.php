<?php

namespace App\Validation;

class FinanceRules
{
    public static function create(): array
    {
        return [
            'type' => [
                'rules' => 'required|in_list[Pemasukan,Pengeluaran]',
                'errors' => [
                    'required' => 'Tipe wajib dipilih',
                    'in_list' => 'Tipe tidak valid',
                ],
            ],
            'category' => [
                'rules' => 'required|max_length[30]',
                'errors' => [
                    'required' => 'Kategori wajib diisi',
                    'max_length' => 'Kategori maksimal 30 karakter',
                ],
            ],
            'description' => [
                'rules' => 'required|max_length[150]',
                'errors' => [
                    'required' => 'Deskripsi wajib diisi',
                    'max_length' => 'Deskripsi maksimal 150 karakter',
                ],
            ],
            'amount' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Jumlah wajib diisi',
                    'numeric' => 'Jumlah harus angka',
                    'greater_than' => 'Jumlah harus lebih dari 0',
                ],
            ],
            'entry_date' => [
                'rules' => 'permit_empty|valid_date[Y-m-d]',
                'errors' => [
                    'valid_date' => 'Format tanggal tidak valid (YYYY-MM-DD)',
                ],
            ],
        ];
    }

    public static function update(): array
    {
        return self::create();
    }
}
