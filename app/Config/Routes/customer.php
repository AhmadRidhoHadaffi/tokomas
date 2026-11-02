<?php

$routes->group('pelanggan', ['filter' => ['auth', 'role:pelanggan']], static function ($routes) {
    $routes->get('', 'Customer\PageController::home');
    $routes->get('pesanan', 'Customer\PageController::orders');
    $routes->get('riwayat', 'Customer\PageController::history');
    $routes->get('notifikasi', 'Customer\PageController::notifications');
    $routes->get('profil', 'Customer\PageController::profile');
});


