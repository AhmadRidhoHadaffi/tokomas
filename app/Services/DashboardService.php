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

        $salesChart = $this->salesLast7Days();
        $cashflowChart = $this->cashflowLast6Months();
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $salesYesterday = $this->paidSalesOn($yesterday);
        $transactionsYesterday = $this->transactionModel->where('DATE(transaction_date)', $yesterday)->countAllResults();
        $salesChangePercent = $salesYesterday > 0
            ? (($this->paidSalesOn($today) - $salesYesterday) / $salesYesterday) * 100
            : null;

        return [
            'sales_today' => (float) ($salesToday['total_sales'] ?? 0),
            'sales_this_month' => (float) ($salesMonth['total_sales'] ?? 0),
            'sales_yesterday' => $salesYesterday,
            'sales_change_percent' => $salesChangePercent,
            'transactions_today' => $transToday,
            'transactions_yesterday' => $transactionsYesterday,
            'active_custom_orders' => $activeOrders,
            'low_stock_count' => $lowStock,
            'total_products' => $totalProducts,
            'recent_transactions' => $recentTransactions,
            'active_order_list' => $activeOrderList,
            'finance_summary' => $financeSummary,
            'sales_last_7_days' => $salesChart,
            'cashflow_last_6_months' => $cashflowChart,
        ];
    }

    /**
     * @return array{labels: string[], values: float[]}
     */
    private function salesLast7Days(): array
    {
        $start = date('Y-m-d', strtotime('-6 days'));
        $rows = $this->transactionModel->builder()
            ->select('DATE(transaction_date) as day, SUM(total) as total')
            ->where('payment_status', 'Lunas')
            ->where('DATE(transaction_date) >=', $start)
            ->groupBy('DATE(transaction_date)')
            ->get()
            ->getResultArray();

        $totals = [];
        foreach ($rows as $row) {
            $totals[$row['day']] = (float) $row['total'];
        }

        $labels = [];
        $values = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime('-' . $i . ' days'));
            $labels[] = date('d M', strtotime($date));
            $values[] = $totals[$date] ?? 0.0;
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * @return array{labels: string[], income: float[], expense: float[]}
     */
    private function cashflowLast6Months(): array
    {
        $start = date('Y-m-01', strtotime('-5 months'));
        $rows = $this->financeModel->builder()
            ->select("DATE_FORMAT(entry_date, '%Y-%m') as month, type, SUM(amount) as total")
            ->where('entry_date >=', $start)
            ->groupBy("DATE_FORMAT(entry_date, '%Y-%m'), type")
            ->get()
            ->getResultArray();

        $income = [];
        $expense = [];
        foreach ($rows as $row) {
            if ($row['type'] === 'Pemasukan') {
                $income[$row['month']] = (float) $row['total'];
            } else {
                $expense[$row['month']] = (float) $row['total'];
            }
        }

        $labels = [];
        $incomeValues = [];
        $expenseValues = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime('-' . $i . ' months'));
            $labels[] = date('M Y', strtotime($month . '-01'));
            $incomeValues[] = $income[$month] ?? 0.0;
            $expenseValues[] = $expense[$month] ?? 0.0;
        }

        return ['labels' => $labels, 'income' => $incomeValues, 'expense' => $expenseValues];
    }

    private function paidSalesOn(string $date): float
    {
        $row = $this->transactionModel->builder()
            ->selectSum('total', 'total_sales')
            ->where('DATE(transaction_date)', $date)
            ->where('payment_status', 'Lunas')
            ->get()
            ->getRowArray();

        return (float) ($row['total_sales'] ?? 0);
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
