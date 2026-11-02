<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Services\PromotionService;

class PromotionController extends BaseController
{
    public function index(): string
    {
        return view('pages/staff/promotions', ['title' => 'Promosi & Notifikasi', 'result' => (new PromotionService())->getAll(['page' => 1, 'per_page' => 20])]);
    }
}
