<?php

$routes->group('', ['filter' => ['guest']], static function ($routes) {
    $routes->get('masuk', 'Public\AuthController::loginForm');
    $routes->post('masuk', 'Public\AuthController::login', ['filter' => ['throttle:5,60']]);
    $routes->get('daftar', 'Public\AuthController::registerForm');
    $routes->post('daftar', 'Public\AuthController::register', ['filter' => ['throttle:5,60']]);
});
$routes->post('keluar', 'Public\AuthController::logout', ['filter' => ['auth']]);


