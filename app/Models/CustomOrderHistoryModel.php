<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomOrderHistoryModel extends Model
{
    protected $table = 'custom_order_histories';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['custom_order_id', 'status', 'changed_by'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // The table only stores created_at (no updated_at column), so the
    // updated field must stay empty or updates would target a missing column.
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
}
