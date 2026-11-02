<?php

// Employees routes - owner only
$routes->group('employees', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner']], static function ($routes) {
    $routes->get('', 'EmployeeController::index');
    $routes->get('(:num)', 'EmployeeController::show/$1');
    $routes->post('', 'EmployeeController::create');
    $routes->put('(:num)', 'EmployeeController::update/$1');
    $routes->put('(:num)/toggle-active', 'EmployeeController::toggleActive/$1');
    $routes->put('(:num)/reset-password', 'EmployeeController::resetPassword/$1');
});

