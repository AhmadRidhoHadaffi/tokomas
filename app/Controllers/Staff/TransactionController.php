<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Services\TransactionService;
use App\Services\CustomerService;
use App\Services\ProductService;
use App\Validation\TransactionRules;

class TransactionController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'payment_status' => $this->request->getGet('payment_status'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'sort' => 'transactions.transaction_date',
            'order' => 'DESC',
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/staff/transactions/index', [
            'title' => 'Catat Transaksi',
            'result' => (new TransactionService())->getAll($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): string
    {
        $customers = (new CustomerService())->getAll(['per_page' => 1000])['items'];
        $products = (new ProductService())->getAll(['per_page' => 1000])['items'];

        return view('pages/staff/transactions/create', [
            'title' => 'Catat Transaksi Baru',
            'customers' => $customers,
            'products' => $products,
        ]);
    }

    public function store()
    {
        $rules = TransactionRules::create();
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'customer_id' => $this->request->getPost('customer_id'),
                'items' => json_decode((string) $this->request->getPost('items'), true) ?: [],
                'discount' => $this->request->getPost('discount') ?: 0,
                'payment_method' => $this->request->getPost('payment_method'),
                'payment_status' => $this->request->getPost('payment_status') ?: 'Lunas',
            ];

            $transaction = (new TransactionService())->create($data, $this->userId());
            return redirect()->to('/karyawan/transaksi')->with('success', 'Transaksi ' . $transaction['invoice_no'] . ' berhasil disimpan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int $id): string
    {
        $transaction = (new TransactionService())->getById($id);
        if (!$transaction) {
            return redirect()->to('/karyawan/transaksi')->with('error', 'Transaksi tidak ditemukan');
        }

        return view('pages/staff/transactions/show', [
            'title' => 'Detail Transaksi',
            'transaction' => $transaction,
        ]);
    }
}


