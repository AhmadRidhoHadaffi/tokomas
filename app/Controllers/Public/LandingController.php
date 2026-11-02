<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\GoldPriceModel;
use App\Models\ProductModel;
use App\Models\PromotionModel;
use App\Models\StoreSettingModel;
use App\Services\AuthService;
use App\Validation\AuthRules;

class LandingController extends BaseController
{
    public function index()
    {
        $products = (new ProductModel())->orderBy('created_at', 'DESC')->findAll(6);
        $promotions = (new PromotionModel())->where('status', 'Terkirim')->orderBy('send_at', 'DESC')->findAll(5);
        $goldPrices = (new GoldPriceModel())->findAll();
        $store = (new StoreSettingModel())->first();

        return $this->render('pages/public/landing', compact('products', 'promotions', 'goldPrices', 'store'));
    }
}
