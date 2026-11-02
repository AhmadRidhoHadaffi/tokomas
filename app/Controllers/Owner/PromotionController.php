<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\PromotionService;

class PromotionController extends BaseController
{
    public function index(): string
    {
        return view('pages/owner/promotions/index', ['title' => 'Promosi & Notifikasi', 'result' => (new PromotionService())->getAll(['page' => 1, 'per_page' => 20])]);
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
