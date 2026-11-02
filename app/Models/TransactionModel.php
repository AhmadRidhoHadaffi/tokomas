<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['invoice_no', 'customer_id', 'user_id', 'transaction_date', 'discount', 'total', 'payment_method', 'payment_status'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function generateInvoiceNo(): string
    {
        $date = date('Ymd');
        $prefix = 'TRX-' . $date . '-';

        $lastInvoice = $this->like('invoice_no', $prefix, 'after')
            ->orderBy('id', 'DESC')
            ->first();

        if ($lastInvoice) {
            $lastNumber = (int) substr($lastInvoice['invoice_no'], -3);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
    }

    public function getWithDetails(int $id): ?array
    {
        $transaction = $this->find($id);
        if (!$transaction) {
            return null;
        }

        $db = $this->db;

        // Join customer
        $customer = $db->table('customers')->where('id', $transaction['customer_id'])->get()->getRowArray();
        $transaction['customer_name'] = $customer['name'] ?? '';

        // Join user (kasir)
        $user = $db->table('users')->where('id', $transaction['user_id'])->get()->getRowArray();
        $transaction['cashier_name'] = $user['name'] ?? '';

        // Get details
        $details = $db->table('transaction_details as td')
            ->select('td.*, p.code, p.name')
            ->join('products as p', 'p.id = td.product_id')
            ->where('td.transaction_id', $id)
            ->get()
            ->getResultArray();

        $transaction['items'] = $details;

        return $transaction;
    }
}
