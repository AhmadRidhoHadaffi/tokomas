<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;

class PageController extends BaseController
{
    public function dashboard() { return view('pages/owner/dashboard/index', ['title' => 'Dashboard Owner']); }
    public function products() { return view('pages/owner/products/index', ['title' => 'Produk & Stok']); }
    public function transactions() { return view('pages/owner/transactions/index', ['title' => 'Transaksi Penjualan']); }
    public function finances() { return view('pages/owner/finances/index', ['title' => 'Pembukuan']); }
    public function customOrders() { return view('pages/owner/custom_orders/index', ['title' => 'Pesanan Custom']); }
    public function customers() { return view('pages/owner/customers/index', ['title' => 'Pelanggan']); }
    public function promotions() { return view('pages/owner/promotions/index', ['title' => 'Promosi & Notifikasi']); }
    public function employees() { return view('pages/owner/employees/index', ['title' => 'Manajemen Karyawan']); }
    public function reports() { return view('pages/owner/reports/index', ['title' => 'Laporan']); }
    public function settings() { return view('pages/owner/settings/index', ['title' => 'Pengaturan']); }
}
