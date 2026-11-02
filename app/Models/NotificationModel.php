<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model
{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['customer_id', 'promotion_id', 'type', 'title', 'message', 'is_read'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
    protected $createdField = 'created_at';

    public function getUnreadCount(int $customerId): int
    {
        return $this->where('customer_id', $customerId)->where('is_read', 0)->countAllResults();
    }

    public function markAsRead(int $id): bool
    {
        return $this->update($id, ['is_read' => 1]);
    }

    public function markAllAsRead(int $customerId): bool
    {
        return $this->where('customer_id', $customerId)->set('is_read', 1)->update();
    }
}
