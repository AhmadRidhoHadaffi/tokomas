<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Navigation extends BaseConfig
{
    public array $owner = [
        ['label' => 'Dashboard', 'url' => '/owner'], ['label' => 'Produk & Stok', 'url' => '/owner/produk'],
        ['label' => 'Transaksi Penjualan', 'url' => '/owner/transaksi'], ['label' => 'Pembukuan', 'url' => '/owner/pembukuan'],
        ['label' => 'Pesanan Custom', 'url' => '/owner/pesanan-custom'], ['label' => 'Pelanggan', 'url' => '/owner/pelanggan'],
        ['label' => 'Promosi & Notifikasi', 'url' => '/owner/promosi'], ['label' => 'Manajemen Karyawan', 'url' => '/owner/karyawan'],
        ['label' => 'Laporan', 'url' => '/owner/laporan'], ['label' => 'Pengaturan', 'url' => '/owner/pengaturan'],
    ];
    public array $karyawan = [
        ['label' => 'Dashboard', 'url' => '/karyawan'], ['label' => 'Produk & Stok', 'url' => '/karyawan/produk'],
        ['label' => 'Catat Transaksi', 'url' => '/karyawan/transaksi'], ['label' => 'Pembukuan', 'url' => '/karyawan/pembukuan'],
        ['label' => 'Pesanan Custom', 'url' => '/karyawan/pesanan-custom'], ['label' => 'Pelanggan', 'url' => '/karyawan/pelanggan'],
        ['label' => 'Promosi & Notifikasi', 'url' => '/karyawan/promosi'],
    ];
    public array $pelanggan = [
        ['label' => 'Beranda', 'url' => '/pelanggan'], ['label' => 'Pesanan', 'url' => '/pelanggan/pesanan'],
        ['label' => 'Riwayat', 'url' => '/pelanggan/riwayat'], ['label' => 'Notifikasi', 'url' => '/pelanggan/notifikasi'],
        ['label' => 'Profil', 'url' => '/pelanggan/profil'],
    ];
}
