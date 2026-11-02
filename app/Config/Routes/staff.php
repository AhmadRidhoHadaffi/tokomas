<?php

$routes->group('karyawan', ['filter' => ['auth', 'role:karyawan']], static function ($routes) {
    $routes->get('', 'Staff\PageController::dashboard');
    $routes->get('produk', 'Staff\ProductController::index');
    $routes->get('transaksi', 'Staff\TransactionController::index');
    $routes->get('transaksi/baru', 'Staff\TransactionController::create');
    $routes->post('transaksi', 'Staff\TransactionController::store');
    $routes->get('transaksi/(:num)', 'Staff\TransactionController::show/$1');
    $routes->get('pembukuan', 'Staff\FinanceController::index');
    $routes->get('pembukuan/baru', 'Staff\FinanceController::create');
    $routes->post('pembukuan', 'Staff\FinanceController::store');
    $routes->get('pesanan-custom', 'Staff\PageController::customOrders');
    $routes->get('pelanggan', 'Staff\PageController::customers');
    $routes->get('promosi', 'Staff\PageController::promotions');
});
