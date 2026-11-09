<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\CustomOrderModel;
use App\Models\ProductModel;
use App\Models\FinanceModel;

class DashboardService
{
    protected TransactionModel $transactionModel;
    protected CustomOrderModel $customOrderModel;
    protected ProductModel $productModel;
    protected FinanceModel $financeModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->customOrderModel = new CustomOrderModel();
        $this->productModel = new ProductModel();
        $this->financeModel = new FinanceModel();
    }

    public function getOwnerStats(): array
    {
        $today = date('Y-m-d');
        $thisMonth = date('Y-m-01');

        // Transactions today
        $salesTodayBuilder = $this->transactionModel->builder();
        $salesTodayBuilder->selectSum('total', 'total_sales')
            ->where('DATE(transaction_date)', $today)
            ->where('payment_status', 'Lunas');
        $salesToday = $salesTodayBuilder->get()->getRowArray();

        // Transactions this month
        $salesMonthBuilder = $this->transactionModel->builder();
        $salesMonthBuilder->selectSum('total', 'total_sales')
            ->where('DATE(transaction_date) >=', $thisMonth)
            ->where('payment_status', 'Lunas');
        $salesMonth = $salesMonthBuilder->get()->getRowArray();

        // Transaction count today
        $transToday = $this->transactionModel->where('DATE(transaction_date)', $today)->countAllResults();

        // Active custom orders
        $activeOrders = $this->customOrderModel->whereNotIn('status', ['Diambil'])->countAllResults();

        // Low stock products
        $lowStock = count($this->productModel->getLowStock(3));
        $totalProducts = $this->productModel->countAllResults();

        // Latest transactions and active custom orders for the dashboard tables
        $recentTransactions = $this->transactionModel->builder()
            ->select('transactions.id, transactions.invoice_no, transactions.total, transactions.payment_status, transactions.transaction_date, customers.name as customer_name')
            ->join('customers', 'customers.id = transactions.customer_id', 'left')
            ->orderBy('transactions.transaction_date', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        $activeOrderList = $this->customOrderModel->builder()
            ->select('custom_orders.id, custom_orders.order_no, custom_orders.status, custom_orders.order_date, customers.name as customer_name')
            ->join('customers', 'customers.id = custom_orders.customer_id', 'left')
            ->whereNotIn('custom_orders.status', ['Diambil'])
            ->orderBy('custom_orders.order_date', 'DESC')
            ->limit(5)
            ->get()
            ->getResultArray();

        // Finance summary this month
        $financeSummary = $this->financeModel->getSummary($thisMonth, $today);

        return [
            'sales_today' => (float) ($salesToday['total_sales'] ?? 0),
            'sales_this_month' => (float) ($salesMonth['total_sales'] ?? 0),
            'transactions_today' => $transToday,
            'active_custom_orders' => $activeOrders,
            'low_stock_count' => $lowStock,
            'total_products' => $totalProducts,
            'recent_transactions' => $recentTransactions,
            'active_order_list' => $activeOrderList,
            'finance_summary' => $financeSummary,
        ];
    }

    public function getKaryawanStats(): array
    {
        $today = date('Y-m-d');

        $salesTodayBuilder = $this->transactionModel->builder();
        $salesTodayBuilder->selectSum('total', 'total_sales')
            ->where('DATE(transaction_date)', $today)
            ->where('payment_status', 'Lunas');
        $salesToday = $salesTodayBuilder->get()->getRowArray();

        $transToday = $this->transactionModel->where('DATE(transaction_date)', $today)->countAllResults();
        $activeOrders = $this->customOrderModel->whereNotIn('status', ['Diambil'])->countAllResults();
        $lowStock = count($this->productModel->getLowStock(3));

        return [
            'sales_today' => (float) ($salesToday['total_sales'] ?? 0),
            'transactions_today' => $transToday,
            'active_custom_orders' => $activeOrders,
            'low_stock_count' => $lowStock,
        ];
    }
}
