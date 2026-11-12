<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomOrderService;
use App\Services\CustomerService;
use App\Services\UploadService;
use App\Validation\CustomOrderRules;

class OrderController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $filters = [
            'customer_id' => $customer['id'],
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/customer/orders', [
            'title' => 'Pesanan Custom Saya',
            'result' => (new CustomOrderService())->getAll($filters),
        ]);
    }

    public function create(): string|\CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->customer()) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return view('pages/customer/orders/form', [
            'title' => 'Ajukan Pesanan Custom',
            'order' => null,
        ]);
    }

    public function store()
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $data = $this->payload();
        $data['customer_id'] = (int) $customer['id'];

        if (!$this->validateData($data, CustomOrderRules::create())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $upload = new UploadService();
            $image = $upload->storeImage($this->request->getFile('reference_image'), 'custom-orders');
            if ($image !== null) {
                $data['reference_image'] = $image;
            }

            $order = (new CustomOrderService())->create($data, (int) $this->userId());

            return redirect()->to('/pelanggan/pesanan/' . $order['id'])
                ->with('success', 'Pesanan ' . $order['order_no'] . ' berhasil diajukan');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = $this->customer();
        $order = (new CustomOrderService())->getById($id);

        if (!$customer || !$order || (int) $order['customer_id'] !== (int) $customer['id']) {
            return redirect()->to('/pelanggan/pesanan')->with('error', 'Pesanan tidak ditemukan');
        }

        if ($order['status'] !== 'Masuk') {
            return redirect()->to('/pelanggan/pesanan/' . $id)
                ->with('error', 'Pesanan yang sudah diproses tidak dapat diubah');
        }

        return view('pages/customer/orders/form', [
            'title' => 'Ubah Pesanan Custom',
            'order' => $order,
        ]);
    }

    public function update(int $id)
    {
        $customer = $this->customer();
        $service = new CustomOrderService();
        $order = $service->getById($id);

        if (!$customer || !$order || (int) $order['customer_id'] !== (int) $customer['id']) {
            return redirect()->to('/pelanggan/pesanan')->with('error', 'Pesanan tidak ditemukan');
        }

        if ($order['status'] !== 'Masuk') {
            return redirect()->to('/pelanggan/pesanan/' . $id)
                ->with('error', 'Pesanan yang sudah diproses tidak dapat diubah');
        }

        $data = $this->payload();

        if (!$this->validateData($data, CustomOrderRules::update())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $upload = new UploadService();
            $image = $upload->storeImage($this->request->getFile('reference_image'), 'custom-orders');
            if ($image !== null) {
                $data['reference_image'] = $image;
            }

            $service->update($id, $data);

            if ($image !== null) {
                $upload->deleteImage('custom-orders', $order['reference_image'] ?? null);
            }

            return redirect()->to('/pelanggan/pesanan/' . $id)->with('success', 'Pesanan berhasil diperbarui');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function show(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = $this->customer();
        $order = (new CustomOrderService())->getById($id);

        if (!$order || !$customer || $order['customer_id'] != $customer['id']) {
            return redirect()->to('/pelanggan/pesanan')->with('error', 'Pesanan tidak ditemukan');
        }

        return view('pages/customer/order_detail', [
            'title' => 'Detail Pesanan',
            'order' => $order,
        ]);
    }

    private function customer(): ?array
    {
        return (new CustomerService())->getByUserId((int) $this->userId());
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'jewelry_type' => $this->request->getPost('jewelry_type'),
            'model' => trim((string) $this->request->getPost('model')),
            'engraving_name' => $this->request->getPost('engraving_name') ?: null,
            'purity' => $this->request->getPost('purity'),
            'weight' => $this->request->getPost('weight') ?: null,
            'estimated_price' => $this->request->getPost('estimated_price'),
            'order_date' => $this->request->getPost('order_date') ?: date('Y-m-d'),
            'due_date' => $this->request->getPost('due_date') ?: null,
            'notes' => $this->request->getPost('notes') ?: null,
        ];
    }
}
