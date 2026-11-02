<?php

namespace App\Models;

use CodeIgniter\Model;

class FinanceModel extends Model
{
    protected $table = 'finances';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['user_id', 'transaction_id', 'custom_order_id', 'type', 'category', 'description', 'amount', 'entry_date'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getSummary(string $startDate, string $endDate): array
    {
        $db = $this->db;

        $income = $db->table($this->table)
            ->selectSum('amount', 'total')
            ->where('type', 'Pemasukan')
            ->where('entry_date >=', $startDate)
            ->where('entry_date <=', $endDate)
            ->get()
            ->getRowArray();

        $expense = $db->table($this->table)
            ->selectSum('amount', 'total')
            ->where('type', 'Pengeluaran')
            ->where('entry_date >=', $startDate)
            ->where('entry_date <=', $endDate)
            ->get()
            ->getRowArray();

        $incomeTotal = (float) ($income['total'] ?? 0);
        $expenseTotal = (float) ($expense['total'] ?? 0);

        return [
            'income' => $incomeTotal,
            'expense' => $expenseTotal,
            'balance' => $incomeTotal - $expenseTotal,
        ];
    }
}
