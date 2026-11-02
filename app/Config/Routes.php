<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// API v1 routes
$routes->group('api/v1', ['namespace' => 'App\Controllers\Api\V1'], static function ($routes) {
    require APPPATH . 'Config/Routes/Api/V1/auth.php';
    require APPPATH . 'Config/Routes/Api/V1/public.php';
    require APPPATH . 'Config/Routes/Api/V1/products.php';
    require APPPATH . 'Config/Routes/Api/V1/transactions.php';
    require APPPATH . 'Config/Routes/Api/V1/finances.php';
    require APPPATH . 'Config/Routes/Api/V1/customOrders.php';
    require APPPATH . 'Config/Routes/Api/V1/customers.php';
    require APPPATH . 'Config/Routes/Api/V1/promotions.php';
    require APPPATH . 'Config/Routes/Api/V1/notifications.php';
    require APPPATH . 'Config/Routes/Api/V1/employees.php';
    require APPPATH . 'Config/Routes/Api/V1/dashboard.php';
    require APPPATH . 'Config/Routes/Api/V1/settings.php';
    require APPPATH . 'Config/Routes/Api/V1/reports.php';
});
