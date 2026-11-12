<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\SettingService;
use App\Validation\StoreSettingRules;

class SettingController extends BaseController
{
    private const GOLD_PURITIES = ['24K', '22K', '18K', '17K'];

    public function index(): string
    {
        $service = new SettingService();

        return view('pages/owner/settings/index', [
            'title' => 'Pengaturan',
            'store' => $service->getStoreSettings(),
            'goldPrices' => $service->getGoldPrices(),
            'purities' => self::GOLD_PURITIES,
        ]);
    }

    public function update()
    {
        if (!$this->validate(StoreSettingRules::update())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $prices = $this->request->getPost('prices');
        $prices = is_array($prices) ? $prices : [];

        foreach ($prices as $purity => $value) {
            if (!in_array($purity, self::GOLD_PURITIES, true)) {
                continue;
            }

            if ($value === null || $value === '') {
                continue;
            }

            if (!is_numeric($value) || (float) $value <= 0) {
                return redirect()->back()->withInput()->with('error', 'Harga emas ' . $purity . ' harus berupa angka lebih dari 0');
            }
        }

        try {
            $service = new SettingService();
            $service->updateStoreSettings([
                'store_name' => trim((string) $this->request->getPost('store_name')),
                'email' => $this->request->getPost('email') ?: null,
                'phone' => $this->request->getPost('phone') ?: null,
                'whatsapp' => $this->request->getPost('whatsapp') ?: null,
                'address' => $this->request->getPost('address') ?: null,
                'notify_status_change' => (int) ($this->request->getPost('notify_status_change') ? 1 : 0),
                'notify_payment' => (int) ($this->request->getPost('notify_payment') ? 1 : 0),
                'notify_low_stock' => (int) ($this->request->getPost('notify_low_stock') ? 1 : 0),
            ]);

            $userId = (int) $this->userId();
            foreach ($prices as $purity => $value) {
                if (!in_array($purity, self::GOLD_PURITIES, true) || $value === null || $value === '') {
                    continue;
                }

                $service->updateGoldPrice($purity, (float) $value, $userId);
            }

            return redirect()->to('/owner/pengaturan')->with('success', 'Pengaturan berhasil disimpan');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }
}
