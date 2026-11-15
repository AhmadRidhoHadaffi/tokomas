<?php

namespace App\Controllers\Public;

use App\Controllers\BaseController;
use App\Models\PromotionModel;
use App\Services\LandingService;

class LandingController extends BaseController
{
    public function index()
    {
        $data = (new LandingService())->data(6);
        $data['promotions'] = (new PromotionModel())->where('status', 'Terkirim')->orderBy('send_at', 'DESC')->findAll(5);
        $data['showAllLink'] = true;

        return $this->render('pages/public/landing', $data);
    }

    public function products()
    {
        $data = (new LandingService())->data();
        $data['sectionHeading'] = 'Semua Koleksi';
        $data['sectionDescription'] = 'Seluruh koleksi emas dan perak yang tersedia di Toko Mas Gembira. Pembelian dilakukan langsung di toko atau tanya ketersediaan via WhatsApp. Ingin model sendiri? Ajukan pesanan custom dari akun Anda.';
        $data['showAllLink'] = false;

        return $this->render('pages/public/products', $data);
    }

    public function custom()
    {
        return $this->render('pages/public/custom', (new LandingService())->data(3));
    }

    public function about()
    {
        return $this->render('pages/public/about', (new LandingService())->data(3));
    }

    public function contact()
    {
        return $this->render('pages/public/contact', (new LandingService())->data(3));
    }
}
