<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function dashboard() { return view('pages/staff/dashboard', ['title' => 'Dashboard Karyawan']); }
    public function products() { return view('pages/staff/products', ['title' => 'Produk & Stok']); }
    public function transactions() { return view('pages/staff/transactions', ['title' => 'Catat Transaksi']); }
    public function finances() { return view('pages/staff/finances', ['title' => 'Pembukuan']); }
    public function customOrders() { return view('pages/staff/custom_orders', ['title' => 'Pesanan Custom']); }
    public function customers() { return view('pages/staff/customers', ['title' => 'Pelanggan']); }
    public function promotions() { return view('pages/staff/promotions', ['title' => 'Promosi & Notifikasi']); }
}
