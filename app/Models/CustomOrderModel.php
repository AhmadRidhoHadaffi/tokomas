<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomOrderModel extends Model
{
    protected $table = 'custom_orders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['order_no', 'customer_id', 'jewelry_type', 'model', 'engraving_name', 'purity', 'weight', 'estimated_price', 'dp', 'status', 'order_date', 'due_date', 'reference_image', 'notes'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function generateOrderNo(): string
    {
        $year = date('Y');
        $prefix = 'PC-' . $year . '-';

        $lastOrder = $this->like('order_no', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastOrder) {
            $lastNumber = (int) substr($lastOrder['order_no'], -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getWithHistory(int $id): ?array
    {
        $order = $this->find($id);
        if (!$order) {
            return null;
        }

        $db = $this->db;

        // Join customer
        $customer = $db->table('customers')->where('id', $order['customer_id'])->get()->getRowArray();
        $order['customer_name'] = $customer['name'] ?? '';
        $order['customer_phone'] = $customer['phone'] ?? '';

        // Get history
        $history = $db->table('custom_order_histories as h')
            ->select('h.status, h.created_at, u.name as changed_by_name')
            ->join('users as u', 'u.id = h.changed_by')
            ->where('h.custom_order_id', $id)
            ->orderBy('h.created_at', 'ASC')
            ->get()
            ->getResultArray();

        $order['history'] = $history;

        return $order;
    }
}
