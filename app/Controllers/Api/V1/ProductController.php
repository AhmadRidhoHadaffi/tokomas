<?php

namespace App\Controllers\Api\V1;

use App\Services\ProductService;
use App\Validation\ProductRules;
use App\Libraries\FileUploader;

class ProductController extends BaseApiController
{
    protected ProductService $productService;
    protected FileUploader $uploader;

    public function __construct()
    {
        $this->productService = new ProductService();
        $this->uploader = new FileUploader();
    }

    public function index()
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'category' => $this->request->getGet('category'),
            'purity' => $this->request->getGet('purity'),
            'stock_level' => $this->request->getGet('stock_level'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->productService->getAll($filters);

        return $this->respondSuccess(
            $result['items'],
            '',
            200,
            [
                'total' => $result['total'],
                'page' => $result['page'],
                'per_page' => $result['per_page'],
                'total_pages' => $result['total_pages'],
            ]
        );
    }

    public function show($id = null)
    {
        $product = $this->productService->getById($id);

        if (!$product) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($product);
    }

    public function create()
    {
        $rules = ProductRules::create();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);

            // Handle image upload
            $image = $this->request->getFile('image');
            if ($image && $image->isValid()) {
                $data['image'] = $this->uploader->upload($image, FCPATH . 'uploads/products');
            }

            $id = $this->productService->create($data);
            $product = $this->productService->getById($id);

            return $this->respondCreated($product);
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 500);
        }
    }

    public function update($id = null)
    {
        $product = $this->productService->getById($id);
        if (!$product) {
            return $this->respondNotFound();
        }

        $rules = ProductRules::update();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);

            // Handle image upload
            $image = $this->request->getFile('image');
            if ($image && $image->isValid()) {
                // Delete old image
                if ($product['image']) {
                    $this->uploader->delete(FCPATH . 'uploads/products', $product['image']);
                }
                $data['image'] = $this->uploader->upload($image, FCPATH . 'uploads/products');
            }

            $this->productService->update($id, $data);
            $updated = $this->productService->getById($id);

            return $this->respondSuccess($updated, 'Produk berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 500);
        }
    }

    public function delete($id = null)
    {
        $product = $this->productService->getById($id);
        if (!$product) {
            return $this->respondNotFound();
        }

        try {
            $this->productService->delete($id);
            return $this->respondNoContent('Produk berhasil dihapus');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 500);
        }
    }
}
