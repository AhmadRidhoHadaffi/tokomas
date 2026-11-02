<?php

namespace App\Services;

use App\Models\NotificationModel;

class NotificationService
{
    protected NotificationModel $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new NotificationModel();
    }

    public function getByCustomer(int $customerId, array $filters = []): array
    {
        $builder = $this->notificationModel->builder();
        $builder->where('customer_id', $customerId);

        if (isset($filters['is_read'])) {
            $builder->where('is_read', (int)$filters['is_read']);
        }

        $builder->orderBy('created_at', 'DESC');

        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $notifications = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'items' => $notifications,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function markAsRead(int $id, int $customerId): bool
    {
        $notif = $this->notificationModel->find($id);
        if (!$notif || $notif['customer_id'] != $customerId) {
            return false;
        }
        return $this->notificationModel->markAsRead($id);
    }

    public function markAllAsRead(int $customerId): bool
    {
        return $this->notificationModel->markAllAsRead($customerId);
    }

    public function getUnreadCount(int $customerId): int
    {
        return $this->notificationModel->getUnreadCount($customerId);
    }
}
