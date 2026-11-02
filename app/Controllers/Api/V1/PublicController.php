<?php

namespace App\Controllers\Api\V1;

use App\Models\ProductModel;
use App\Models\PromotionModel;
use App\Models\GoldPriceModel;
use App\Models\StoreSettingModel;
use App\Services\ProductService;

class PublicController extends BaseApiController
{
    public function products()
    {
        $productService = new ProductService();
        $result = $productService->getAll(['per_page' => 6, 'sort' => 'created_at', 'order' => 'DESC']);
        return $this->respondSuccess($result['items']);
    }

    public function promotions()
    {
        $promoModel = new PromotionModel();
        $promos = $promoModel->where('status', 'Terkirim')->orderBy('send_at', 'DESC')->limit(5)->findAll();
        return $this->respondSuccess($promos);
    }

    public function goldPrices()
    {
        $goldPriceModel = new GoldPriceModel();
        $prices = $goldPriceModel->findAll();
        return $this->respondSuccess($prices);
    }

    public function storeInfo()
    {
        $settingModel = new StoreSettingModel();
        $settings = $settingModel->getSettings();
        return $this->respondSuccess($settings);
    }
}
