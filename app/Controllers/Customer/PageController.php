<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function home() { return view('pages/customer/home', ['title' => 'Beranda Pelanggan']); }
    public function orders() { return view('pages/customer/orders', ['title' => 'Pesanan Saya']); }
    public function history() { return view('pages/customer/history', ['title' => 'Riwayat Transaksi']); }
    public function notifications() { return view('pages/customer/notifications', ['title' => 'Notifikasi']); }
    public function profile() { return view('pages/customer/profile', ['title' => 'Profil Saya']); }
}
