<?php

$routes->group('karyawan', ['filter' => ['auth', 'role:karyawan']], static function ($routes) {
    $routes->get('', 'Staff\PageController::dashboard');
    $routes->get('produk', 'Staff\ProductController::index');
    $routes->get('transaksi', 'Staff\PageController::transactions');
    $routes->get('pembukuan', 'Staff\PageController::finances');
    $routes->get('pesanan-custom', 'Staff\PageController::customOrders');
    $routes->get('pelanggan', 'Staff\PageController::customers');
    $routes->get('promosi', 'Staff\PageController::promotions');
});
