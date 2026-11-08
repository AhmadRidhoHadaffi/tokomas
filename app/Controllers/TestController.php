<?php
namespace App\Controllers;
class TestController extends BaseController {
    public function ownerDashboard() {
        return view('pages/owner/dashboard/index', [
            'stats' => [
                'today_sales' => 15000000,
                'today_transactions' => 12,
                'pending_custom_orders' => 3,
                'low_stock_products' => 5
            ],
            'recent_transactions' => [
                ['invoice_number' => 'INV-001', 'customer_name' => 'John Doe', 'total_amount' => 5000000, 'status' => 'completed'],
                ['invoice_number' => 'INV-002', 'customer_name' => 'Jane Smith', 'total_amount' => 3000000, 'status' => 'pending']
            ]
        ]);
    }
    public function ownerProducts() {
        return view('pages/owner/products/index', [
            'result' => [
                'total' => 25,
                'items' => [
                    ['code' => 'G001', 'name' => 'Cincin Emas', 'purity' => '24K', 'price' => 5000000, 'stock' => 10],
                    ['code' => 'G002', 'name' => 'Kalung Emas', 'purity' => '22K', 'price' => 8000000, 'stock' => 3],
                    ['code' => 'G003', 'name' => 'Gelang Emas', 'purity' => '24K', 'price' => 12000000, 'stock' => 0]
                ]
            ],
            'filters' => ['search' => '', 'stock_level' => '']
        ]);
    }
    public function staffDashboard() {
        return view('pages/staff/dashboard', [
            'stats' => ['today_transactions' => 8, 'active_orders' => 5, 'low_stock' => 3, 'new_customers' => 2],
            'tasks' => [
                ['icon' => '📦', 'title' => 'Order Custom Menunggu', 'description' => '2 pesanan perlu diproses'],
                ['icon' => '⚠️', 'title' => 'Stok Menipis', 'description' => '3 produk perlu restock']
            ]
        ]);
    }
    public function customerHome() {
        return view('pages/customer/home', [
            'user' => ['name' => 'Ahmad', 'email' => 'ahmad@test.com'],
            'recent_orders' => [
                ['order_code' => 'CO-001', 'order_date' => '2026-11-01', 'total' => 5000000, 'status' => 'production']
            ]
        ]);
    }
}
