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

    // The table only stores created_at (no updated_at column), so the
    // updated field must stay empty or mark-as-read would target a missing column.
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';

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
