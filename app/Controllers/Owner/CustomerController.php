<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\CustomerService;
use App\Validation\CustomerRules;

class CustomerController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/owner/customers/index', [
            'title' => 'Pelanggan',
            'result' => (new CustomerService())->getAll($filters),
            'filters' => $filters,
        ]);
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = (new CustomerService())->getById($id);
        if (!$customer) {
            return redirect()->to('/owner/pelanggan')->with('error', 'Pelanggan tidak ditemukan');
        }

        return view('pages/owner/customers/form', [
            'title' => 'Ubah Pelanggan',
            'customer' => $customer,
        ]);
    }

    public function update(int $id)
    {
        if (!$this->validate(CustomerRules::update($id))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $service = new CustomerService();
            if (!$service->getById($id)) {
                throw new \RuntimeException('Pelanggan tidak ditemukan');
            }

            $service->update($id, [
                'name' => trim((string) $this->request->getPost('name')),
                'phone' => trim((string) $this->request->getPost('phone')),
                'email' => $this->request->getPost('email') ?: null,
                'address' => $this->request->getPost('address') ?: null,
            ]);

            return redirect()->to('/owner/pelanggan')->with('success', 'Data pelanggan berhasil diperbarui');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
