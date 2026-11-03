<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Navigation extends BaseConfig
{
    public array $owner = [
        ['label' => 'Dashboard', 'url' => '/owner', 'icon' => 'home'],
        ['label' => 'Produk & Stok', 'url' => '/owner/produk', 'icon' => 'package'],
        ['label' => 'Transaksi Penjualan', 'url' => '/owner/transaksi', 'icon' => 'wallet'],
        ['label' => 'Pembukuan', 'url' => '/owner/pembukuan', 'icon' => 'file-text'],
        ['label' => 'Pesanan Custom', 'url' => '/owner/pesanan-custom', 'icon' => 'gem'],
        ['label' => 'Pelanggan', 'url' => '/owner/pelanggan', 'icon' => 'user-plus'],
        ['label' => 'Promosi & Notifikasi', 'url' => '/owner/promosi', 'icon' => 'gift'],
        ['label' => 'Manajemen Karyawan', 'url' => '/owner/karyawan', 'icon' => 'user-plus'],
        ['label' => 'Laporan', 'url' => '/owner/laporan', 'icon' => 'file-spreadsheet'],
        ['label' => 'Pengaturan', 'url' => '/owner/pengaturan', 'icon' => 'settings'],
    ];

    public array $karyawan = [
        ['label' => 'Dashboard', 'url' => '/karyawan', 'icon' => 'home'],
        ['label' => 'Produk & Stok', 'url' => '/karyawan/produk', 'icon' => 'package'],
        ['label' => 'Catat Transaksi', 'url' => '/karyawan/transaksi', 'icon' => 'receipt-text'],
        ['label' => 'Pembukuan', 'url' => '/karyawan/pembukuan', 'icon' => 'file-text'],
        ['label' => 'Pesanan Custom', 'url' => '/karyawan/pesanan-custom', 'icon' => 'gem'],
        ['label' => 'Pelanggan', 'url' => '/karyawan/pelanggan', 'icon' => 'user-plus'],
        ['label' => 'Promosi & Notifikasi', 'url' => '/karyawan/promosi', 'icon' => 'gift'],
    ];

    public array $pelanggan = [
        ['label' => 'Beranda', 'url' => '/pelanggan', 'icon' => 'home'],
        ['label' => 'Pesanan', 'url' => '/pelanggan/pesanan', 'icon' => 'gem'],
        ['label' => 'Riwayat', 'url' => '/pelanggan/riwayat', 'icon' => 'file-text'],
        ['label' => 'Notifikasi', 'url' => '/pelanggan/notifikasi', 'icon' => 'bell'],
        ['label' => 'Profil', 'url' => '/pelanggan/profil', 'icon' => 'user-plus'],
    ];
}
