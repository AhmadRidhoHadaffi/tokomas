<?php

// Auth routes - no authentication required
$routes->group('auth', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    $routes->post('register', 'AuthController::register', ['filter' => 'throttle:5,60']);
    $routes->post('login', 'AuthController::login', ['filter' => 'throttle:5,60']);
    
    // Authenticated routes
    $routes->group('', ['filter' => 'jwt'], static function ($routes) {
        $routes->get('me', 'AuthController::me');
        $routes->post('logout', 'AuthController::logout');
        $routes->put('password', 'AuthController::changePassword');
    });
});
