<?php

namespace App\Services;

use App\Models\ProductModel;

class ProductService
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->productModel->builder();

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('code', $filters['search'])
                ->orLike('name', $filters['search'])
                ->groupEnd();
        }

        // Filter by category
        if (!empty($filters['category'])) {
            $builder->where('category', $filters['category']);
        }

        // Filter by purity
        if (!empty($filters['purity'])) {
            $builder->where('purity', $filters['purity']);
        }

        // Filter by stock level
        if (!empty($filters['stock_level'])) {
            switch ($filters['stock_level']) {
                case 'habis':
                    $builder->where('stock', 0);
                    break;
                case 'hampir':
                    $builder->where('stock >', 0)->where('stock <=', 3);
                    break;
                case 'tersedia':
                    $builder->where('stock >', 3);
                    break;
            }
        }

        // Sorting
        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        // Pagination
        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $products = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Transform products (map purity to metal + karat)
        $products = array_map([$this, 'transformProduct'], $products);

        return [
            'items' => $products,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?array
    {
        $product = $this->productModel->find($id);
        return $product ? $this->transformProduct($product) : null;
    }

    public function create(array $data): int
    {
        // Transform metal + karat to purity
        if (isset($data['metal']) && isset($data['karat'])) {
            $data['purity'] = $this->metalKaratToPurity($data['metal'], $data['karat']);
            unset($data['metal'], $data['karat']);
        }

        return $this->productModel->insert($data);
    }

    public function update(int $id, array $data): bool
    {
        // Transform metal + karat to purity
        if (isset($data['metal']) && isset($data['karat'])) {
            $data['purity'] = $this->metalKaratToPurity($data['metal'], $data['karat']);
            unset($data['metal'], $data['karat']);
        }

        return $this->productModel->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->productModel->delete($id);
    }

    protected function transformProduct(array $product): array
    {
        [$metal, $karat] = $this->purityToMetalKarat($product['purity']);
        
        $product['metal'] = $metal;
        $product['karat'] = $karat;
        
        // Calculate stock level
        if ($product['stock'] == 0) {
            $product['stock_level'] = 'habis';
        } elseif ($product['stock'] <= 3) {
            $product['stock_level'] = 'hampir';
        } else {
            $product['stock_level'] = 'tersedia';
        }

        // Format image URL
        if ($product['image']) {
            $product['image_url'] = base_url('uploads/products/' . $product['image']);
        } else {
            $product['image_url'] = null;
        }

        return $product;
    }

    protected function purityToMetalKarat(string $purity): array
    {
        if ($purity === 'Perak 925') {
            return ['Perak', '925'];
        }
        return ['Emas', $purity];
    }

    protected function metalKaratToPurity(string $metal, string $karat): string
    {
        if ($metal === 'Perak') {
            return 'Perak 925';
        }
        return $karat;
    }
}
