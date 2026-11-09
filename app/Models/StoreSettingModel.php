<?php

namespace App\Models;

use CodeIgniter\Model;

class StoreSettingModel extends Model
{
    protected $table = 'store_settings';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['store_name', 'email', 'phone', 'whatsapp', 'address', 'notify_status_change', 'notify_payment', 'notify_low_stock'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // The table only stores updated_at (no created_at column).
    protected $useTimestamps = true;
    protected $createdField = '';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getSettings(): ?array
    {
        return $this->first();
    }
}
