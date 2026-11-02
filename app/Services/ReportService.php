<?php

namespace App\Services;

use App\Models\TransactionModel;
use App\Models\FinanceModel;
use App\Models\ProductModel;
use App\Models\CustomOrderModel;

class ReportService
{
    protected TransactionModel $transactionModel;
    protected FinanceModel $financeModel;
    protected ProductModel $productModel;
    protected CustomOrderModel $customOrderModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->financeModel = new FinanceModel();
        $this->productModel = new ProductModel();
        $this->customOrderModel = new CustomOrderModel();
    }

    public function salesReport(string $startDate, string $endDate): array
    {
        $builder = $this->transactionModel->builder();
        $builder->select('transactions.*, customers.name as customer_name, users.name as cashier_name')
            ->join('customers', 'customers.id = transactions.customer_id')
            ->join('users', 'users.id = transactions.user_id')
            ->where('DATE(transactions.transaction_date) >=', $startDate)
            ->where('DATE(transactions.transaction_date) <=', $endDate)
            ->orderBy('transactions.transaction_date', 'DESC');

        $transactions = $builder->get()->getResultArray();

        $totalSales = array_sum(array_column(array_filter($transactions, fn($t) => $t['payment_status'] === 'Lunas'), 'total'));
        $totalDiscount = array_sum(array_column($transactions, 'discount'));

        return [
            'summary' => ['total_transactions' => count($transactions), 'total_sales' => (float)$totalSales, 'total_discount' => (float)$totalDiscount],
            'details' => $transactions,
        ];
    }

    public function financeReport(string $startDate, string $endDate): array
    {
        $builder = $this->financeModel->builder();
        $builder->select('finances.*, users.name as recorded_by_name')
            ->join('users', 'users.id = finances.user_id')
            ->where('finances.entry_date >=', $startDate)
            ->where('finances.entry_date <=', $endDate)
            ->orderBy('finances.entry_date', 'DESC');

        $finances = $builder->get()->getResultArray();
        $summary = $this->financeModel->getSummary($startDate, $endDate);

        return ['summary' => $summary, 'details' => $finances];
    }

    public function stockReport(): array
    {
        $products = $this->productModel->orderBy('stock', 'ASC')->findAll();

        $totalProducts = count($products);
        $outOfStock = count(array_filter($products, fn($p) => $p['stock'] == 0));
        $lowStock = count(array_filter($products, fn($p) => $p['stock'] > 0 && $p['stock'] <= 3));

        return [
            'summary' => ['total_products' => $totalProducts, 'out_of_stock' => $outOfStock, 'low_stock' => $lowStock],
            'details' => $products,
        ];
    }

    public function customOrderReport(string $startDate, string $endDate): array
    {
        $builder = $this->customOrderModel->builder();
        $builder->select('custom_orders.*, customers.name as customer_name, customers.phone as customer_phone')
            ->join('customers', 'customers.id = custom_orders.customer_id')
            ->where('custom_orders.order_date >=', $startDate)
            ->where('custom_orders.order_date <=', $endDate)
            ->orderBy('custom_orders.order_date', 'DESC');

        $orders = $builder->get()->getResultArray();

        $totalOrders = count($orders);
        $totalEstimatedValue = array_sum(array_column($orders, 'estimated_price'));
        $totalDp = array_sum(array_column($orders, 'dp'));

        return [
            'summary' => ['total_orders' => $totalOrders, 'total_estimated_value' => (float)$totalEstimatedValue, 'total_dp' => (float)$totalDp],
            'details' => $orders,
        ];
    }
}
