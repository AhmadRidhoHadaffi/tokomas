<?php

$routes->get('/', 'Public\LandingController::index');
$routes->get('produk', 'Public\LandingController::products');
$routes->get('custom', 'Public\LandingController::custom');
$routes->get('tentang', 'Public\LandingController::about');
$routes->get('kontak', 'Public\LandingController::contact');
