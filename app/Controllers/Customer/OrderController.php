<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomOrderService;
use App\Services\CustomerService;

class OrderController extends BaseController
{
    public function index(): string
    {
        $customer = (new CustomerService())->getByUserId($this->userId());
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $filters = ['customer_id' => $customer['id'], 'page' => max(1, (int) ($this->request->getGet('page') ?? 1)), 'per_page' => 20];

        return view('pages/customer/orders', [
            'title' => 'Pesanan Custom Saya',
            'result' => (new CustomOrderService())->getAll($filters),
        ]);
    }

    public function show(int $id): string
    {
        $customer = (new CustomerService())->getByUserId($this->userId());
        $order = (new CustomOrderService())->getById($id);

        if (!$order || !$customer || $order['customer_id'] != $customer['id']) {
            return redirect()->to('/pelanggan/pesanan')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('pages/customer/order_detail', [
            'title' => 'Detail Pesanan',
            'order' => $order,
        ]);
    }
}
