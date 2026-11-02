<?php

// Custom Orders routes - owner & karyawan
$routes->group('custom-orders', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']], static function ($routes) {
    $routes->get('', 'CustomOrderController::index');
    $routes->get('(:num)', 'CustomOrderController::show/$1');
    $routes->post('', 'CustomOrderController::create');
    $routes->put('(:num)', 'CustomOrderController::update/$1');
    $routes->put('(:num)/status', 'CustomOrderController::changeStatus/$1');
});

