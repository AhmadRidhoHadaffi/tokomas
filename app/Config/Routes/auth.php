<?php

$routes->group('', ['filter' => ['guest']], static function ($routes) {
    $routes->get('masuk', 'Public\AuthController::loginForm');
    $routes->post('masuk', 'Public\AuthController::login', ['filter' => ['throttle:5,60']]);
    $routes->get('daftar', 'Public\AuthController::registerForm');
    $routes->post('daftar', 'Public\AuthController::register', ['filter' => ['throttle:5,60']]);

    // Verifikasi OTP pendaftaran
    $routes->get('verifikasi-otp', 'Public\AuthController::verifyOtpForm');
    $routes->post('verifikasi-otp', 'Public\AuthController::verifyOtp', ['filter' => ['throttle:6,60']]);
    $routes->post('kirim-ulang-otp', 'Public\AuthController::resendOtp', ['filter' => ['throttle:3,60']]);

    // Lupa & reset kata sandi lewat OTP nomor HP
    $routes->get('lupa-sandi', 'Public\AuthController::forgotPasswordForm');
    $routes->post('lupa-sandi', 'Public\AuthController::forgotPassword', ['filter' => ['throttle:5,60']]);
    $routes->get('reset-sandi', 'Public\AuthController::resetPasswordForm');
    $routes->post('reset-sandi', 'Public\AuthController::resetPassword', ['filter' => ['throttle:5,60']]);
    $routes->post('kirim-ulang-reset', 'Public\AuthController::resendResetOtp', ['filter' => ['throttle:3,60']]);
});

$routes->post('keluar', 'Public\AuthController::logout', ['filter' => ['auth']]);
