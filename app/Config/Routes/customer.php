<?php

$routes->group('pelanggan', ['filter' => ['auth', 'role:pelanggan']], static function ($routes) {
    $routes->get('', 'Customer\PageController::home');
    $routes->get('pesanan', 'Customer\OrderController::index');
    $routes->get('pesanan/(:num)', 'Customer\OrderController::show/$1');
    $routes->get('riwayat', 'Customer\PageController::history');
    $routes->get('notifikasi', 'Customer\PageController::notifications');
    $routes->get('profil', 'Customer\PageController::profile');
});
