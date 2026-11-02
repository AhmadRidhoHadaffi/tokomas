<?php

$routes->group('owner', ['filter' => ['auth', 'role:owner']], static function ($routes) {
    $routes->get('', 'Owner\PageController::dashboard');
    $routes->get('produk', 'Owner\ProductController::index');
    $routes->get('transaksi', 'Owner\TransactionController::index');
    $routes->get('transaksi/baru', 'Owner\TransactionController::create');
    $routes->post('transaksi', 'Owner\TransactionController::store');
    $routes->get('transaksi/(:num)', 'Owner\TransactionController::show/$1');
    $routes->get('pembukuan', 'Owner\FinanceController::index');
    $routes->get('pembukuan/baru', 'Owner\FinanceController::create');
    $routes->post('pembukuan', 'Owner\FinanceController::store');
    $routes->post('pembukuan/(:num)/hapus', 'Owner\FinanceController::delete/$1');
    $routes->get('pesanan-custom', 'Owner\CustomOrderController::index');
    $routes->get('pesanan-custom/baru', 'Owner\CustomOrderController::create');
    $routes->post('pesanan-custom', 'Owner\CustomOrderController::store');
    $routes->get('pesanan-custom/(:num)', 'Owner\CustomOrderController::show/$1');
    $routes->post('pesanan-custom/(:num)/ubah-status', 'Owner\CustomOrderController::changeStatus/$1');
    $routes->get('pelanggan', 'Owner\PageController::customers');
    $routes->get('promosi', 'Owner\PageController::promotions');
    $routes->get('karyawan', 'Owner\PageController::employees');
    $routes->get('laporan', 'Owner\PageController::reports');
    $routes->get('pengaturan', 'Owner\PageController::settings');
});
