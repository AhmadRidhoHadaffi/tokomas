<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomerService;

class ProfileController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = (new CustomerService())->getByUserId($this->userId());
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return view('pages/customer/profile', ['title' => 'Profil Saya', 'customer' => $customer]);
    }
}
