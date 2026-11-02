<?php

// Settings routes - owner only
$routes->group('settings', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner']], static function ($routes) {
    $routes->get('store', 'SettingController::getStore');
    $routes->put('store', 'SettingController::updateStore');
    $routes->get('gold-prices', 'SettingController::getGoldPrices');
    $routes->put('gold-prices', 'SettingController::updateGoldPrices');
});

