<?php

namespace App\Controllers\Api\V1;

use App\Services\SettingService;

class SettingController extends BaseApiController
{
    protected SettingService $settingService;

    public function __construct()
    {
        $this->settingService = new SettingService();
    }

    public function getStore()
    {
        $settings = $this->settingService->getStoreSettings();
        return $this->respondSuccess($settings);
    }

    public function updateStore()
    {
        $data = $this->request->getJSON(true);
        try {
            $this->settingService->updateStoreSettings($data);
            return $this->respondSuccess($this->settingService->getStoreSettings(), 'Pengaturan toko diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function getGoldPrices()
    {
        $prices = $this->settingService->getGoldPrices();
        return $this->respondSuccess($prices);
    }

    public function updateGoldPrices()
    {
        $data = $this->request->getJSON(true);
        $userId = $this->getUserId();

        try {
            foreach ($data['prices'] as $item) {
                $this->settingService->updateGoldPrice($item['purity'], $item['price_per_gram'], $userId);
            }
            return $this->respondSuccess($this->settingService->getGoldPrices(), 'Harga emas diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }
}
