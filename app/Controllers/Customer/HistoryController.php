<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomerService;
use App\Services\TransactionService;

class HistoryController extends BaseController
{
    public function index(): string
    {
        $customer = (new CustomerService())->getByUserId($this->userId());
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $filters = ['customer_id' => $customer['id'], 'page' => max(1, (int) ($this->request->getGet('page') ?? 1)), 'per_page' => 20];

        return view('pages/customer/history', [
            'title' => 'Riwayat Transaksi',
            'result' => (new TransactionService())->getAll($filters),
        ]);
    }
}
