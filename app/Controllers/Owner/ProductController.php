<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\ProductService;
use App\Services\UploadService;
use App\Validation\ProductRules;

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
            'per_page' => min(100, max(1, (int) ($this->request->getGet('per_page') ?? 20))),
        ];

        return view('pages/owner/products/index', [
            'title' => 'Produk & Stok',
            'result' => (new ProductService())->getAll($filters),
            'filters' => $filters,
            'categories' => ['Cincin', 'Kalung', 'Gelang', 'Anting', 'Liontin'],
            'purities' => ['24K', '22K', '18K', '17K', 'Perak 925'],
        ]);
    }

    public function create(): string
    {
        return view('pages/owner/products/form', [
            'title' => 'Tambah Produk',
            'product' => null,
        ]);
    }

    public function store()
    {
        if (!$this->validate(ProductRules::create())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = $this->payload();
            $image = (new UploadService())->storeImage($this->request->getFile('image'), 'products');
            if ($image !== null) {
                $data['image'] = $image;
            }

            (new ProductService())->create($data);

            return redirect()->to('/owner/produk')->with('success', 'Produk berhasil ditambahkan');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function edit(int $id): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $product = (new ProductService())->getById($id);
        if (!$product) {
            return redirect()->to('/owner/produk')->with('error', 'Produk tidak ditemukan');
        }

        return view('pages/owner/products/form', [
            'title' => 'Ubah Produk',
            'product' => $product,
        ]);
    }

    public function update(int $id)
    {
        if (!$this->validate(ProductRules::update())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $service = new ProductService();
            $product = $service->getById($id);
            if (!$product) {
                throw new \RuntimeException('Produk tidak ditemukan');
            }

            $data = $this->payload();
            $upload = new UploadService();
            $image = $upload->storeImage($this->request->getFile('image'), 'products');
            if ($image !== null) {
                $data['image'] = $image;
                $upload->deleteImage('products', $product['image'] ?? null);
            }

            $service->update($id, $data);

            return redirect()->to('/owner/produk')->with('success', 'Produk berhasil diperbarui');
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        try {
            (new ProductService())->delete($id);
            return redirect()->to('/owner/produk')->with('success', 'Produk berhasil dihapus');
        } catch (\Throwable $e) {
            return redirect()->to('/owner/produk')->with('error', $e->getMessage());
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function payload(): array
    {
        return [
            'code' => strtoupper(trim((string) $this->request->getPost('code'))),
            'name' => trim((string) $this->request->getPost('name')),
            'category' => $this->request->getPost('category'),
            'metal' => $this->request->getPost('metal'),
            'karat' => $this->request->getPost('karat'),
            'weight' => $this->request->getPost('weight'),
            'size' => $this->request->getPost('size') ?: null,
            'model' => $this->request->getPost('model') ?: null,
            'price' => $this->request->getPost('price'),
            'stock' => (int) $this->request->getPost('stock'),
        ];
    }
}
