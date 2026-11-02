<?php

namespace App\Controllers\Ajax;

use App\Controllers\BaseController;
use App\Services\CustomerService;
use App\Services\NotificationService;
use CodeIgniter\HTTP\ResponseInterface;

class NotificationController extends BaseController
{
    public function unreadCount(): ResponseInterface
    {
        $customer = (new CustomerService())->getByUserId((int) $this->userId());
        $count = $customer ? (new NotificationService())->getUnreadCount((int) $customer['id']) : 0;

        return $this->response->setJSON(['success' => true, 'data' => ['count' => $count], 'message' => 'OK']);
    }
}
