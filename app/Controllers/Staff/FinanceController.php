<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Services\FinanceService;
use App\Validation\FinanceRules;

class FinanceController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'type' => $this->request->getGet('type'),
            'category' => $this->request->getGet('category'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'sort' => 'finances.entry_date',
            'order' => 'DESC',
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        $service = new FinanceService();
        $summary = $service->getSummary(
            $filters['start_date'] ?: date('Y-m-01'),
            $filters['end_date'] ?: date('Y-m-d')
        );

        return view('pages/staff/finances/index', [
            'title' => 'Pembukuan',
            'result' => $service->getAll($filters),
            'summary' => $summary,
            'filters' => $filters,
            'categories' => $service->getCategories(),
        ]);
    }

    public function create(): string
    {
        $service = new FinanceService();
        return view('pages/staff/finances/create', [
            'title' => 'Tambah Catatan Pembukuan',
            'categories' => $service->getCategories(),
        ]);
    }

    public function store()
    {
        $rules = FinanceRules::create();
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'type' => $this->request->getPost('type'),
                'category' => $this->request->getPost('category'),
                'description' => $this->request->getPost('description'),
                'amount' => $this->request->getPost('amount'),
                'entry_date' => $this->request->getPost('entry_date') ?: date('Y-m-d'),
            ];

            (new FinanceService())->create($data, $this->userId());
            return redirect()->to('/karyawan/pembukuan')->with('success', 'Catatan pembukuan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
