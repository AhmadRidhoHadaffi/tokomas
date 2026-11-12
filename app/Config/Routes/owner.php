<?php

$routes->group('owner', ['filter' => ['auth', 'role:owner']], static function ($routes) {
    $routes->get('', 'Owner\DashboardController::index');

    $routes->get('produk', 'Owner\ProductController::index');
    $routes->get('produk/baru', 'Owner\ProductController::create');
    $routes->post('produk', 'Owner\ProductController::store');
    $routes->get('produk/(:num)/ubah', 'Owner\ProductController::edit/$1');
    $routes->post('produk/(:num)', 'Owner\ProductController::update/$1');
    $routes->post('produk/(:num)/hapus', 'Owner\ProductController::delete/$1');

    $routes->get('transaksi', 'Owner\TransactionController::index');
    $routes->get('transaksi/baru', 'Owner\TransactionController::create');
    $routes->post('transaksi', 'Owner\TransactionController::store');
    $routes->get('transaksi/(:num)', 'Owner\TransactionController::show/$1');

    $routes->get('pembukuan', 'Owner\FinanceController::index');
    $routes->get('pembukuan/baru', 'Owner\FinanceController::create');
    $routes->post('pembukuan', 'Owner\FinanceController::store');
    $routes->get('pembukuan/(:num)/ubah', 'Owner\FinanceController::edit/$1');
    $routes->post('pembukuan/(:num)', 'Owner\FinanceController::update/$1');
    $routes->post('pembukuan/(:num)/hapus', 'Owner\FinanceController::delete/$1');

    $routes->get('pesanan-custom', 'Owner\CustomOrderController::index');
    $routes->get('pesanan-custom/baru', 'Owner\CustomOrderController::create');
    $routes->post('pesanan-custom', 'Owner\CustomOrderController::store');
    $routes->get('pesanan-custom/(:num)', 'Owner\CustomOrderController::show/$1');
    $routes->post('pesanan-custom/(:num)/ubah-status', 'Owner\CustomOrderController::changeStatus/$1');

    $routes->get('pelanggan', 'Owner\CustomerController::index');
    $routes->get('pelanggan/(:num)/ubah', 'Owner\CustomerController::edit/$1');
    $routes->post('pelanggan/(:num)', 'Owner\CustomerController::update/$1');

    $routes->get('promosi', 'Owner\PromotionController::index');
    $routes->get('promosi/baru', 'Owner\PromotionController::create');
    $routes->post('promosi', 'Owner\PromotionController::store');
    $routes->post('promosi/(:num)/kirim', 'Owner\PromotionController::send/$1');

    $routes->get('karyawan', 'Owner\EmployeeController::index');
    $routes->get('karyawan/baru', 'Owner\EmployeeController::create');
    $routes->post('karyawan', 'Owner\EmployeeController::store');
    $routes->get('karyawan/(:num)/ubah', 'Owner\EmployeeController::edit/$1');
    $routes->post('karyawan/(:num)', 'Owner\EmployeeController::update/$1');
    $routes->post('karyawan/(:num)/toggle', 'Owner\EmployeeController::toggle/$1');
    $routes->get('karyawan/(:num)/kata-sandi', 'Owner\EmployeeController::passwordForm/$1');
    $routes->post('karyawan/(:num)/kata-sandi', 'Owner\EmployeeController::resetPassword/$1');

    $routes->get('laporan', 'Owner\ReportController::index');
    $routes->get('pengaturan', 'Owner\SettingController::index');
    $routes->post('pengaturan', 'Owner\SettingController::update');
});
