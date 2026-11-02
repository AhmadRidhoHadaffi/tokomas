<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\SettingService;

class SettingController extends BaseController
{
    public function index(): string
    {
        $service = new SettingService();
        return view('pages/owner/settings/index', ['title' => 'Pengaturan', 'store' => $service->getStoreSettings(), 'goldPrices' => $service->getGoldPrices()]);
    }
}
