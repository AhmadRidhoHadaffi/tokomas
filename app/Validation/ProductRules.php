<?php

namespace App\Validation;

class ProductRules
{
    public static function create(): array
    {
        return [
            'code' => [
                'rules' => 'required|max_length[10]|is_unique[products.code]',
                'errors' => [
                    'required' => 'Kode produk wajib diisi',
                    'max_length' => 'Kode maksimal 10 karakter',
                    'is_unique' => 'Kode sudah digunakan',
                ],
            ],
            'name' => [
                'rules' => 'required|max_length[100]',
                'errors' => [
                    'required' => 'Nama produk wajib diisi',
                    'max_length' => 'Nama maksimal 100 karakter',
                ],
            ],
            'category' => [
                'rules' => 'required|in_list[Cincin,Kalung,Gelang,Anting,Liontin]',
                'errors' => [
                    'required' => 'Kategori wajib diisi',
                    'in_list' => 'Kategori tidak valid',
                ],
            ],
            'metal' => [
                'rules' => 'required|in_list[Emas,Perak]',
                'errors' => [
                    'required' => 'Jenis logam wajib diisi',
                    'in_list' => 'Jenis logam tidak valid',
                ],
            ],
            'karat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kadar wajib diisi',
                ],
            ],
            'weight' => [
                'rules' => 'required|decimal|greater_than[0]',
                'errors' => [
                    'required' => 'Berat wajib diisi',
                    'decimal' => 'Berat harus angka desimal',
                    'greater_than' => 'Berat harus lebih dari 0',
                ],
            ],
            'price' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Harga wajib diisi',
                    'numeric' => 'Harga harus angka',
                    'greater_than' => 'Harga harus lebih dari 0',
                ],
            ],
            'stock' => [
                'rules' => 'required|integer|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Stok wajib diisi',
                    'integer' => 'Stok harus bilangan bulat',
                    'greater_than_equal_to' => 'Stok tidak boleh negatif',
                ],
            ],
            'size' => [
                'rules' => 'permit_empty|max_length[20]',
            ],
            'model' => [
                'rules' => 'permit_empty|max_length[50]',
            ],
        ];
    }

    public static function update(): array
    {
        $rules = self::create();
        $rules['code']['rules'] = 'required|max_length[10]';
        return $rules;
    }
}
