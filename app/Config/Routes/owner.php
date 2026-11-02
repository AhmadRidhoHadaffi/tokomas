<?php

$routes->group('owner', ['filter' => ['auth', 'role:owner']], static function ($routes) {
    $routes->get('', 'Owner\PageController::dashboard');
    $routes->get('produk', 'Owner\ProductController::index');
    $routes->get('transaksi', 'Owner\PageController::transactions');
    $routes->get('pembukuan', 'Owner\PageController::finances');
    $routes->get('pesanan-custom', 'Owner\PageController::customOrders');
    $routes->get('pelanggan', 'Owner\PageController::customers');
    $routes->get('promosi', 'Owner\PageController::promotions');
    $routes->get('karyawan', 'Owner\PageController::employees');
    $routes->get('laporan', 'Owner\PageController::reports');
    $routes->get('pengaturan', 'Owner\PageController::settings');
});
