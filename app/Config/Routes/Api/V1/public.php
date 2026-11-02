<?php

// Public routes - no auth
$routes->group('public', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    $routes->get('products', 'PublicController::products');
    $routes->get('promotions', 'PublicController::promotions');
    $routes->get('gold-prices', 'PublicController::goldPrices');
    $routes->get('store-info', 'PublicController::storeInfo');
});
