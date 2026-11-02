<?php

// Promotions routes - owner & karyawan
$routes->group('promotions', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']], static function ($routes) {
    $routes->get('', 'PromotionController::index');
    $routes->get('(:num)', 'PromotionController::show/$1');
    $routes->post('', 'PromotionController::create');
    $routes->put('(:num)', 'PromotionController::update/$1');
    $routes->delete('(:num)', 'PromotionController::delete/$1');
    $routes->post('(:num)/send', 'PromotionController::send/$1');
});

