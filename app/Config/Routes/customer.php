<?php

$routes->group('pelanggan', ['filter' => ['auth', 'role:pelanggan']], static function ($routes) {
    $routes->get('', 'Customer\HomeController::index');
    $routes->get('pesanan', 'Customer\OrderController::index');
    $routes->get('pesanan/(:num)', 'Customer\OrderController::show/$1');
    $routes->get('riwayat', 'Customer\HistoryController::index');
    $routes->get('notifikasi', 'Customer\NotificationController::index');
    $routes->get('profil', 'Customer\ProfileController::index');
});
