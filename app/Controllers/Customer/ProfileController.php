<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Services\AuthService;
use App\Services\CustomerService;
use App\Validation\ProfileRules;

class ProfileController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return view('pages/customer/profile', [
            'title' => 'Profil Saya',
            'customer' => $customer,
        ]);
    }

    public function update()
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan/profil')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $id = (int) $customer['id'];

        if (!$this->validate(ProfileRules::update($id, (int) $this->userId()))) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $name = trim((string) $this->request->getPost('name'));
        $email = strtolower(trim((string) $this->request->getPost('email')));

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            (new CustomerService())->update($id, [
                'name' => $name,
                'phone' => trim((string) $this->request->getPost('phone')),
                'email' => $email,
                'address' => $this->request->getPost('address') ?: null,
            ]);

            (new UserModel())->update((int) $this->userId(), [
                'name' => $name,
                'email' => $email,
            ]);

            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \RuntimeException('Profil gagal diperbarui');
            }

            $this->session->set([
                'user_name' => $name,
                'user_email' => $email,
            ]);

            return redirect()->to('/pelanggan/profil')->with('success', 'Profil berhasil diperbarui');
        } catch (\Throwable $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function updatePassword()
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        if (!$this->validate(ProfileRules::password())) {
            return redirect()->back()->with('errors', $this->validator->getErrors());
        }

        $changed = (new AuthService())->changePassword(
            (int) $this->userId(),
            (string) $this->request->getPost('current_password'),
            (string) $this->request->getPost('new_password')
        );

        if (!$changed) {
            return redirect()->back()->with('error', 'Kata sandi saat ini tidak sesuai');
        }

        return redirect()->to('/pelanggan/profil')->with('success', 'Kata sandi berhasil diperbarui');
    }

    private function customer(): ?array
    {
        return (new CustomerService())->getByUserId((int) $this->userId());
    }
}
