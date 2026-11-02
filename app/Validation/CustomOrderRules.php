<?php

namespace App\Validation;

class CustomOrderRules
{
    public static function create(): array
    {
        return [
            'customer_id' => [
                'rules' => 'required|integer|is_not_unique[customers.id]',
                'errors' => [
                    'required' => 'Customer wajib dipilih',
                    'integer' => 'Customer ID tidak valid',
                    'is_not_unique' => 'Customer tidak ditemukan',
                ],
            ],
            'jewelry_type' => [
                'rules' => 'required|in_list[Cincin,Kalung,Gelang,Anting,Liontin]',
                'errors' => [
                    'required' => 'Jenis perhiasan wajib dipilih',
                    'in_list' => 'Jenis perhiasan tidak valid',
                ],
            ],
            'model' => [
                'rules' => 'required|max_length[50]',
                'errors' => [
                    'required' => 'Model wajib diisi',
                    'max_length' => 'Model maksimal 50 karakter',
                ],
            ],
            'purity' => [
                'rules' => 'required|in_list[24K,22K,18K,17K,Perak 925]',
                'errors' => [
                    'required' => 'Kadar wajib dipilih',
                    'in_list' => 'Kadar tidak valid',
                ],
            ],
            'estimated_price' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Estimasi harga wajib diisi',
                    'numeric' => 'Estimasi harga harus angka',
                    'greater_than' => 'Estimasi harga harus lebih dari 0',
                ],
            ],
            'dp' => [
                'rules' => 'permit_empty|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'numeric' => 'DP harus angka',
                    'greater_than_equal_to' => 'DP tidak boleh negatif',
                ],
            ],
            'weight' => [
                'rules' => 'permit_empty|decimal|greater_than[0]',
                'errors' => [
                    'decimal' => 'Berat harus angka desimal',
                    'greater_than' => 'Berat harus lebih dari 0',
                ],
            ],
            'order_date' => [
                'rules' => 'permit_empty|valid_date[Y-m-d]',
            ],
            'due_date' => [
                'rules' => 'permit_empty|valid_date[Y-m-d]',
            ],
        ];
    }

    public static function update(): array
    {
        $rules = self::create();
        unset($rules['customer_id']); // Can't change customer
        return $rules;
    }

    public static function changeStatus(): array
    {
        return [
            'status' => [
                'rules' => 'required|in_list[Masuk,Diproses,Dalam Pembuatan,Selesai,Diambil]',
                'errors' => [
                    'required' => 'Status wajib dipilih',
                    'in_list' => 'Status tidak valid',
                ],
            ],
        ];
    }
}
