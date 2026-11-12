<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Models\CustomerModel;
use App\Services\CustomerService;
use App\Services\PromotionService;
use App\Validation\PromotionRules;

class PromotionController extends BaseController
{
    public function index(): string
    {
        return view('pages/owner/promotions/index', [
            'title' => 'Promosi & Notifikasi',
            'result' => (new PromotionService())->getAll(['page' => 1, 'per_page' => 20]),
        ]);
    }

    public function create(): string
    {
        return view('pages/owner/promotions/form', [
            'title' => 'Buat Promosi',
            'customers' => (new CustomerService())->getAll(['per_page' => 1000])['items'],
        ]);
    }

    public function store()
    {
        if (!$this->validate(PromotionRules::create())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $targetType = (string) $this->request->getPost('target_type');
        $customerIds = $this->request->getPost('customer_ids');
        $customerIds = is_array($customerIds) ? array_values(array_unique(array_map('intval', $customerIds))) : [];

        if ($targetType === 'terpilih' && $customerIds === []) {
            return redirect()->back()->withInput()->with('error', 'Pilih minimal satu pelanggan untuk target terpilih');
        }

        if ($targetType === 'terpilih') {
            $existing = (new CustomerModel())->whereIn('id', $customerIds)->countAllResults();
            if ($existing !== count($customerIds)) {
                return redirect()->back()->withInput()->with('error', 'Ada pelanggan terpilih yang tidak ditemukan');
            }
        } else {
            $customerIds = [];
        }

        try {
            $service = new PromotionService();
            $service->create([
                'title' => trim((string) $this->request->getPost('title')),
                'message' => trim((string) $this->request->getPost('message')),
                'target_type' => $targetType,
                'status' => 'Draf',
                'customer_ids' => $customerIds,
            ], (int) $this->userId());

            return redirect()->to('/owner/promosi')->with('success', 'Promosi berhasil dibuat. Kirim saat sudah siap.');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function send(int $id)
    {
        try {
            $count = (new PromotionService())->send($id);
            return redirect()->to('/owner/promosi')->with('success', $count . ' notifikasi promosi dibuat');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
