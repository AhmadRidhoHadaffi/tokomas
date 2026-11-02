<?php

// Reports routes - owner only
$routes->group('reports', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner']], static function ($routes) {
    $routes->get('sales', 'ReportController::sales');
    $routes->get('finances', 'ReportController::finances');
    $routes->get('stock', 'ReportController::stock');
    $routes->get('custom-orders', 'ReportController::customOrders');
});

