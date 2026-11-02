<?php

namespace App\Models;

use CodeIgniter\Model;

class PromotionCustomerModel extends Model
{
    protected $table = 'promotion_customers';
    protected $primaryKey = ['promotion_id', 'customer_id'];
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['promotion_id', 'customer_id'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
