<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\CustomerService;

class CustomerController extends BaseController
{
    public function index(): string
    {
        $filters = ['search' => $this->request->getGet('search'), 'page' => max(1, (int) ($this->request->getGet('page') ?? 1)), 'per_page' => 20];
        return view('pages/staff/customers/index', ['title' => 'Pelanggan', 'result' => (new CustomerService())->getAll($filters), 'filters' => $filters]);
    }
}

