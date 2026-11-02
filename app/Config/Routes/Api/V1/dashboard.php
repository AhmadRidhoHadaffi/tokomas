<?php

// Dashboard routes - owner & karyawan
$routes->get('dashboard', 'DashboardController::index', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:owner,karyawan']]);

