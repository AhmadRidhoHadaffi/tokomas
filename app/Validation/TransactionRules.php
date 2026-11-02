<?php

namespace App\Validation;

class TransactionRules
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
            'items' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Item transaksi wajib diisi',
                ],
            ],
            'discount' => [
                'rules' => 'permit_empty|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'numeric' => 'Diskon harus angka',
                    'greater_than_equal_to' => 'Diskon tidak boleh negatif',
                ],
            ],
            'payment_method' => [
                'rules' => 'required|in_list[Tunai,Transfer,QRIS]',
                'errors' => [
                    'required' => 'Metode pembayaran wajib dipilih',
                    'in_list' => 'Metode pembayaran tidak valid',
                ],
            ],
            'payment_status' => [
                'rules' => 'permit_empty|in_list[Lunas,Menunggu]',
                'errors' => [
                    'in_list' => 'Status pembayaran tidak valid',
                ],
            ],
        ];
    }
}
