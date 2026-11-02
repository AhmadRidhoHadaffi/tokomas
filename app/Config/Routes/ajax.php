<?php

$routes->group('ajax', ['filter' => ['auth']], static function ($routes) {
    $routes->get('notifikasi/belum-dibaca', 'Ajax\NotificationController::unreadCount', ['filter' => ['role:pelanggan']]);
});
