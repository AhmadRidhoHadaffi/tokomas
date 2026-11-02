<?php

namespace App\Models;

use CodeIgniter\Model;

class GoldPriceModel extends Model
{
    protected $table = 'gold_prices';
    protected $primaryKey = 'purity';
    protected $useAutoIncrement = false;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['purity', 'price_per_gram', 'updated_by'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = false;
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
}
