<?php

$routes->group('pelanggan', ['filter' => ['auth', 'role:pelanggan']], static function ($routes) {
    $routes->get('', 'Customer\HomeController::index');

    $routes->get('pesanan', 'Customer\OrderController::index');
    $routes->get('pesanan/baru', 'Customer\OrderController::create');
    $routes->post('pesanan', 'Customer\OrderController::store');
    $routes->get('pesanan/(:num)/ubah', 'Customer\OrderController::edit/$1');
    $routes->post('pesanan/(:num)', 'Customer\OrderController::update/$1');
    $routes->get('pesanan/(:num)', 'Customer\OrderController::show/$1');

    $routes->get('riwayat', 'Customer\HistoryController::index');

    $routes->get('notifikasi', 'Customer\NotificationController::index');
    $routes->post('notifikasi/dibaca-semua', 'Customer\NotificationController::markAll');
    $routes->post('notifikasi/(:num)/dibaca', 'Customer\NotificationController::markRead/$1');

    $routes->get('profil', 'Customer\ProfileController::index');
    $routes->post('profil', 'Customer\ProfileController::update');
    $routes->post('profil/kata-sandi', 'Customer\ProfileController::updatePassword');
});
