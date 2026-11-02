<?php

// Customers routes - owner & karyawan
$routes->group('customers', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']], static function ($routes) {
    $routes->get('', 'CustomerController::index');
    $routes->get('(:num)', 'CustomerController::show/$1');
    $routes->put('(:num)', 'CustomerController::update/$1');
});

