<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\FinanceModel;

class TransactionService
{
    protected TransactionModel $transactionModel;
    protected TransactionDetailModel $detailModel;
    protected ProductModel $productModel;
    protected CustomerModel $customerModel;
    protected FinanceModel $financeModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->detailModel = new TransactionDetailModel();
        $this->productModel = new ProductModel();
        $this->customerModel = new CustomerModel();
        $this->financeModel = new FinanceModel();
    }

    public function create(array $data, int $userId): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Validate customer
            $customer = $this->customerModel->find($data['customer_id']);
            if (!$customer) {
                throw new \Exception('Customer tidak ditemukan');
            }

            // Validate items and stock
            $items = $data['items'] ?? [];
            if (empty($items)) {
                throw new \Exception('Minimal 1 item harus dipilih');
            }

            $subtotal = 0;
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);
                if (!$product) {
                    throw new \Exception('Produk ID ' . $item['product_id'] . ' tidak ditemukan');
                }

                if ($product['stock'] < $item['quantity']) {
                    throw new \Exception('Stok ' . $product['name'] . ' tidak cukup (tersedia: ' . $product['stock'] . ')');
                }

                $subtotal += $product['price'] * $item['quantity'];
            }

            // Calculate total
            $discount = $data['discount'] ?? 0;
            $total = $subtotal - $discount;

            if ($total < 0) {
                throw new \Exception('Diskon tidak boleh lebih besar dari subtotal');
            }

            // Generate invoice number
            $invoiceNo = $this->transactionModel->generateInvoiceNo();

            // Insert transaction
            $transactionId = $this->transactionModel->insert([
                'invoice_no' => $invoiceNo,
                'customer_id' => $data['customer_id'],
                'user_id' => $userId,
                'transaction_date' => date('Y-m-d H:i:s'),
                'discount' => $discount,
                'total' => $total,
                'payment_method' => $data['payment_method'],
                'payment_status' => $data['payment_status'] ?? 'Lunas',
            ]);

            if (!$transactionId) {
                throw new \Exception('Gagal membuat transaksi');
            }

            // Insert transaction details and decrease stock
            foreach ($items as $item) {
                $product = $this->productModel->find($item['product_id']);

                $this->detailModel->insert([
                    'transaction_id' => $transactionId,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $product['price'],
                ]);

                // Decrease stock
                if (!$this->productModel->decreaseStock($item['product_id'], $item['quantity'])) {
                    throw new \Exception('Gagal mengurangi stok');
                }
            }

            // Auto-record to finances if Lunas
            if (($data['payment_status'] ?? 'Lunas') === 'Lunas') {
                $this->financeModel->insert([
                    'user_id' => $userId,
                    'transaction_id' => $transactionId,
                    'custom_order_id' => null,
                    'type' => 'Pemasukan',
                    'category' => 'Penjualan',
                    'description' => 'Penjualan ' . $invoiceNo,
                    'amount' => $total,
                    'entry_date' => date('Y-m-d'),
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            return $this->transactionModel->getWithDetails($transactionId);
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->transactionModel->builder();
        $builder->select('transactions.*, customers.name as customer_name, users.name as cashier_name')
            ->join('customers', 'customers.id = transactions.customer_id')
            ->join('users', 'users.id = transactions.user_id');

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('transactions.invoice_no', $filters['search'])
                ->orLike('customers.name', $filters['search'])
                ->groupEnd();
        }

        // Filter by payment status
        if (!empty($filters['payment_status'])) {
            $builder->where('transactions.payment_status', $filters['payment_status']);
        }

        // Filter by payment method
        if (!empty($filters['payment_method'])) {
            $builder->where('transactions.payment_method', $filters['payment_method']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $builder->where('DATE(transactions.transaction_date) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('DATE(transactions.transaction_date) <=', $filters['end_date']);
        }

        // Sorting
        $sortField = $filters['sort'] ?? 'transactions.transaction_date';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        // Pagination
        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $transactions = $builder->limit($perPage, $offset)->get()->getResultArray();

        return [
            'items' => $transactions,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?array
    {
        return $this->transactionModel->getWithDetails($id);
    }
}
