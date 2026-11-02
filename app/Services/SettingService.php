<?php

namespace App\Services;

use App\Models\StoreSettingModel;
use App\Models\GoldPriceModel;

class SettingService
{
    protected StoreSettingModel $settingModel;
    protected GoldPriceModel $goldPriceModel;

    public function __construct()
    {
        $this->settingModel = new StoreSettingModel();
        $this->goldPriceModel = new GoldPriceModel();
    }

    public function getStoreSettings(): ?array
    {
        return $this->settingModel->getSettings();
    }

    public function updateStoreSettings(array $data): bool
    {
        $settings = $this->settingModel->getSettings();
        if ($settings) {
            return $this->settingModel->update($settings['id'], $data);
        }
        return $this->settingModel->insert($data) > 0;
    }

    public function getGoldPrices(): array
    {
        return $this->goldPriceModel->findAll();
    }

    public function updateGoldPrice(string $purity, float $price, int $userId): bool
    {
        $exists = $this->goldPriceModel->find($purity);
        if ($exists) {
            return $this->goldPriceModel->update($purity, ['price_per_gram' => $price, 'updated_by' => $userId]);
        }
        return $this->goldPriceModel->insert(['purity' => $purity, 'price_per_gram' => $price, 'updated_by' => $userId]) > 0;
    }
}
