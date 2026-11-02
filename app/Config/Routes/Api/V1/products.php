<?php

// Products routes - owner & karyawan
$routes->group('products', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']], static function ($routes) {
    $routes->get('', 'ProductController::index');
    $routes->get('(:num)', 'ProductController::show/$1');
    $routes->post('', 'ProductController::create');
    $routes->put('(:num)', 'ProductController::update/$1');
    $routes->delete('(:num)', 'ProductController::delete/$1');
});

