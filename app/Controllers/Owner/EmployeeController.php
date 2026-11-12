<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\EmployeeService;
use App\Validation\EmployeeRules;

class EmployeeController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'is_active' => in_array($this->request->getGet('is_active'), ['0', '1'], true) ? $this->request->getGet('is_active') : null,
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/owner/employees/index', [
            'title' => 'Manajemen Karyawan',
            'result' => (new EmployeeService())->getAll($filters),
            'filters' => $filters,
        ]);
    }

    public function create(): string
    {
        return view('pages/owner/employees/form', [
            'title' => 'Tambah Karyawan',
            'employee' => null,
        ]);
    }

    public function store()
    {
        if (!$this->validate(EmployeeRules::create())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            (new EmployeeService())->create([
                'name' => trim((string) $this->request->getPost('name')),
                'email' => strtolower(trim((string) $this->request->getPost('email'))),
                'password' => (string) $this->request->getPost('password'),
                'is_active' => (int) ($this->request->getPost('is_active') ? 1 : 0),
            ]);

            return redirect()->to('/owner/karyawan')->with('success', 'Karyawan berhasil ditambahkan');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $employee = (new EmployeeService())->getById($id);
        if (!$employee) {
            return redirect()->to('/owner/karyawan')->with('error', 'Karyawan tidak ditemukan');
        }

        return view('pages/owner/employees/form', [
            'title' => 'Ubah Karyawan',
            'employee' => $employee,
        ]);
    }

    public function update(int $id)
    {
        if (!$this->validate(EmployeeRules::update($id))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $service = new EmployeeService();
            if (!$service->getById($id)) {
                throw new \RuntimeException('Karyawan tidak ditemukan');
            }

            $service->update($id, [
                'name' => trim((string) $this->request->getPost('name')),
                'email' => strtolower(trim((string) $this->request->getPost('email'))),
                'is_active' => (int) ($this->request->getPost('is_active') ? 1 : 0),
            ]);

            return redirect()->to('/owner/karyawan')->with('success', 'Data karyawan berhasil diperbarui');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function toggle(int $id)
    {
        try {
            $service = new EmployeeService();
            $employee = $service->getById($id);
            if (!$employee) {
                throw new \RuntimeException('Karyawan tidak ditemukan');
            }

            $service->toggleActive($id);

            return redirect()->to('/owner/karyawan')->with(
                'success',
                $employee['is_active'] ? 'Karyawan dinonaktifkan' : 'Karyawan diaktifkan kembali'
            );
        } catch (\Throwable $e) {
            return redirect()->to('/owner/karyawan')->with('error', $e->getMessage());
        }
    }

    public function passwordForm(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $employee = (new EmployeeService())->getById($id);
        if (!$employee) {
            return redirect()->to('/owner/karyawan')->with('error', 'Karyawan tidak ditemukan');
        }

        return view('pages/owner/employees/password', [
            'title' => 'Reset Kata Sandi',
            'employee' => $employee,
        ]);
    }

    public function resetPassword(int $id)
    {
        if (!$this->validate(EmployeeRules::password())) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        try {
            $service = new EmployeeService();
            if (!$service->resetPassword($id, (string) $this->request->getPost('password'))) {
                throw new \RuntimeException('Karyawan tidak ditemukan');
            }

            return redirect()->to('/owner/karyawan')->with('success', 'Kata sandi karyawan berhasil direset');
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
