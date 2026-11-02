<?php

namespace App\Controllers\Api\V1;

use App\Services\NotificationService;

class NotificationController extends BaseApiController
{
    protected NotificationService $notificationService;

    public function __construct()
    {
        $this->notificationService = new NotificationService();
    }

    public function index()
    {
        $user = $this->getUser();
        if ($user->role !== 'pelanggan') {
            return $this->respondForbidden('Hanya pelanggan yang bisa akses notifikasi');
        }

        // Get customer_id from user
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->findByUserId($user->id);
        if (!$customer) return $this->respondNotFound('Customer tidak ditemukan');

        $filters = [
            'is_read' => $this->request->getGet('is_read'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->notificationService->getByCustomer($customer['id'], $filters);
        return $this->respondSuccess($result['items'], '', 200, ['total' => $result['total'], 'page' => $result['page'], 'per_page' => $result['per_page'], 'total_pages' => $result['total_pages']]);
    }

    public function markRead($id = null)
    {
        $user = $this->getUser();
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->findByUserId($user->id);
        if (!$customer) return $this->respondNotFound();

        if ($this->notificationService->markAsRead($id, $customer['id'])) {
            return $this->respondNoContent('Notifikasi ditandai dibaca');
        }
        return $this->respondNotFound();
    }

    public function markAllRead()
    {
        $user = $this->getUser();
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->findByUserId($user->id);
        if (!$customer) return $this->respondNotFound();

        $this->notificationService->markAllAsRead($customer['id']);
        return $this->respondNoContent('Semua notifikasi ditandai dibaca');
    }

    public function unreadCount()
    {
        $user = $this->getUser();
        $customerModel = new \App\Models\CustomerModel();
        $customer = $customerModel->findByUserId($user->id);
        if (!$customer) return $this->respondNotFound();

        $count = $this->notificationService->getUnreadCount($customer['id']);
        return $this->respondSuccess(['unread_count' => $count]);
    }
}
