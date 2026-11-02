<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Services\CustomOrderService;
use App\Services\CustomerService;
use App\Validation\CustomOrderRules;

class CustomOrderController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'status' => $this->request->getGet('status'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/staff/custom_orders/index', [
            'title' => 'Pesanan Custom',
            'result' => (new CustomOrderService())->getAll($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): string
    {
        $customers = (new CustomerService())->getAll(['per_page' => 1000])['items'];
        return view('pages/staff/custom_orders/create', [
            'title' => 'Tambah Pesanan Custom',
            'customers' => $customers,
        ]);
    }

    public function store()
    {
        $rules = CustomOrderRules::create();
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'customer_id' => $this->request->getPost('customer_id'),
                'jewelry_type' => $this->request->getPost('jewelry_type'),
                'model' => $this->request->getPost('model'),
                'engraving_name' => $this->request->getPost('engraving_name'),
                'purity' => $this->request->getPost('purity'),
                'weight' => $this->request->getPost('weight'),
                'estimated_price' => $this->request->getPost('estimated_price'),
                'dp' => $this->request->getPost('dp') ?: 0,
                'order_date' => $this->request->getPost('order_date') ?: date('Y-m-d'),
                'due_date' => $this->request->getPost('due_date'),
                'notes' => $this->request->getPost('notes'),
            ];

            $order = (new CustomOrderService())->create($data, $this->userId());
            return redirect()->to('/karyawan/pesanan-custom')->with('success', 'Pesanan ' . $order['order_no'] . ' berhasil dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int $id): string
    {
        $order = (new CustomOrderService())->getById($id);
        if (!$order) {
            return redirect()->to('/karyawan/pesanan-custom')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('pages/staff/custom_orders/show', [
            'title' => 'Detail Pesanan Custom',
            'order' => $order,
        ]);
    }

    public function changeStatus(int $id)
    {
        $rules = CustomOrderRules::changeStatus();
        if (!$this->validate($rules)) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        try {
            $newStatus = $this->request->getPost('status');
            (new CustomOrderService())->changeStatus($id, $newStatus, $this->userId());
            return redirect()->to('/karyawan/pesanan-custom/' . $id)->with('success', 'Status berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}


