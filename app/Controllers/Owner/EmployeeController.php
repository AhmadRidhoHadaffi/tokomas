<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\EmployeeService;

class EmployeeController extends BaseController
{
    public function index(): string
    {
        $filters = ['search' => $this->request->getGet('search'), 'page' => max(1, (int) ($this->request->getGet('page') ?? 1)), 'per_page' => 20];
        return view('pages/owner/employees/index', ['title' => 'Manajemen Karyawan', 'result' => (new EmployeeService())->getAll($filters), 'filters' => $filters]);
    }
}
