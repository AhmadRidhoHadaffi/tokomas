<?php

// Finances routes - owner & karyawan
$routes->group('finances', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']], static function ($routes) {
    $routes->get('', 'FinanceController::index');
    $routes->get('summary', 'FinanceController::summary');
    $routes->get('categories', 'FinanceController::categories');
    $routes->get('(:num)', 'FinanceController::show/$1');
    $routes->post('', 'FinanceController::create');
    $routes->put('(:num)', 'FinanceController::update/$1');
    $routes->delete('(:num)', 'FinanceController::delete/$1');
});

