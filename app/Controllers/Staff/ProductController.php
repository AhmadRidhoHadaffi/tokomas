<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Services\ProductService;

class ProductController extends BaseController
{
    public function index(): string
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'purity' => $this->request->getGet('purity'),
            'stock_level' => $this->request->getGet('stock_level'),
            'sort' => in_array($this->request->getGet('sort'), ['name', 'price', 'stock', 'created_at'], true) ? $this->request->getGet('sort') : 'created_at',
            'order' => strtoupper($this->request->getGet('order')) === 'ASC' ? 'ASC' : 'DESC',
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/staff/products/index', [
            'title' => 'Produk & Stok',
            'result' => (new ProductService())->getAll($filters),
            'filters' => $filters,
            'categories' => ['Cincin', 'Kalung', 'Gelang', 'Anting', 'Liontin'],
            'purities' => ['24K', '22K', '18K', '17K', 'Perak 925'],
        ]);
    }
}
