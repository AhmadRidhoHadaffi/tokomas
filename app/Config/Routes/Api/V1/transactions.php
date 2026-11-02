<?php

// Transactions routes - owner & karyawan
$routes->group('transactions', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']], static function ($routes) {
    $routes->get('', 'TransactionController::index');
    $routes->get('(:num)', 'TransactionController::show/$1');
    $routes->post('', 'TransactionController::create');
});

