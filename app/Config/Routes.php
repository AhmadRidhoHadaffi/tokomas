<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
require APPPATH . 'Config/Routes/public.php';
require APPPATH . 'Config/Routes/auth.php';
require APPPATH . 'Config/Routes/owner.php';
require APPPATH . 'Config/Routes/staff.php';
require APPPATH . 'Config/Routes/customer.php';
require APPPATH . 'Config/Routes/ajax.php';

if (ENVIRONMENT === 'development') {
    $routes->get('dev/tombol', 'DevButtonController::index');

    $routes->get('test/owner-dashboard', 'TestController::ownerDashboard');
    $routes->get('test/owner-products', 'TestController::ownerProducts');
    $routes->get('test/staff-dashboard', 'TestController::staffDashboard');
    $routes->get('test/customer-home', 'TestController::customerHome');
}
