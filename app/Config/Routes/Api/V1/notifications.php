<?php

// Notifications routes - pelanggan only
$routes->group('notifications', ['namespace' => 'App\Controllers\Api\V1', 'filter' => ['jwt', 'role:pelanggan']], static function ($routes) {
    $routes->get('', 'NotificationController::index');
    $routes->get('unread-count', 'NotificationController::unreadCount');
    $routes->put('(:num)/read', 'NotificationController::markRead/$1');
    $routes->put('mark-all-read', 'NotificationController::markAllRead');
});

