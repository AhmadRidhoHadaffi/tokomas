<?php

$routes->group('karyawan', ['filter' => ['auth', 'role:karyawan']], static function ($routes) {
    $routes->get('', 'Staff\DashboardController::index');
    $routes->get('produk', 'Staff\ProductController::index');
    $routes->get('transaksi', 'Staff\TransactionController::index');
    $routes->get('transaksi/baru', 'Staff\TransactionController::create');
    $routes->post('transaksi', 'Staff\TransactionController::store');
    $routes->get('transaksi/(:num)', 'Staff\TransactionController::show/$1');
    $routes->get('pembukuan', 'Staff\FinanceController::index');
    $routes->get('pembukuan/baru', 'Staff\FinanceController::create');
    $routes->post('pembukuan', 'Staff\FinanceController::store');
    $routes->get('pesanan-custom', 'Staff\CustomOrderController::index');
    $routes->get('pesanan-custom/baru', 'Staff\CustomOrderController::create');
    $routes->post('pesanan-custom', 'Staff\CustomOrderController::store');
    $routes->get('pesanan-custom/(:num)', 'Staff\CustomOrderController::show/$1');
    $routes->post('pesanan-custom/(:num)/ubah-status', 'Staff\CustomOrderController::changeStatus/$1');
    $routes->get('pelanggan', 'Staff\CustomerController::index');
    $routes->get('promosi', 'Staff\PromotionController::index');
});
