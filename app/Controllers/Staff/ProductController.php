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
            'stock_level' => $this->request->getGet('stock_level'),
            'sort' => 'created_at',
            'order' => 'DESC',
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];

        return view('pages/staff/products/index', [
            'title' => 'Produk & Stok',
            'result' => (new ProductService())->getAll($filters),
            'filters' => $filters,
        ]);
    }
}
