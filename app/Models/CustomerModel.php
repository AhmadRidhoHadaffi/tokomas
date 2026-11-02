<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerModel extends Model
{
    protected $table = 'customers';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'name', 'phone', 'email', 'address'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function findByPhone(string $phone): ?array
    {
        return $this->where('phone', $phone)->first();
    }

    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    public function getWithStats(int $id): ?array
    {
        $customer = $this->find($id);
        if (!$customer) {
            return null;
        }

        $db = $this->db;
        $stats = $db->table('transactions')
            ->select('COUNT(*) as total_transactions, COALESCE(SUM(total), 0) as total_spent')
            ->where('customer_id', $id)
            ->where('payment_status', 'Lunas')
            ->get()
            ->getRowArray();

        $customer['total_transactions'] = (int) $stats['total_transactions'];
        $customer['total_spent'] = (float) $stats['total_spent'];

        return $customer;
    }
}
