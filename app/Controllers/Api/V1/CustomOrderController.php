<?php

namespace App\Controllers\Api\V1;

use App\Services\CustomOrderService;
use App\Validation\CustomOrderRules;
use App\Libraries\FileUploader;

class CustomOrderController extends BaseApiController
{
    protected CustomOrderService $customOrderService;
    protected FileUploader $uploader;

    public function __construct()
    {
        $this->customOrderService = new CustomOrderService();
        $this->uploader = new FileUploader();
    }

    public function index()
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'status' => $this->request->getGet('status'),
            'customer_id' => $this->request->getGet('customer_id'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->customOrderService->getAll($filters);

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
        $order = $this->customOrderService->getById($id);

        if (!$order) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($order);
    }

    public function create()
    {
        $rules = CustomOrderRules::create();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);
            $userId = $this->getUserId();

            // Handle reference image upload
            $image = $this->request->getFile('reference_image');
            if ($image && $image->isValid()) {
                $data['reference_image'] = $this->uploader->upload($image, FCPATH . 'uploads/custom-orders');
            }

            $order = $this->customOrderService->create($data, $userId);

            return $this->respondCreated($order, 'Pesanan custom berhasil dibuat');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function update($id = null)
    {
        $order = $this->customOrderService->getById($id);
        if (!$order) {
            return $this->respondNotFound();
        }

        $rules = CustomOrderRules::update();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);

            // Handle reference image upload
            $image = $this->request->getFile('reference_image');
            if ($image && $image->isValid()) {
                // Delete old image
                if ($order['reference_image']) {
                    $this->uploader->delete(FCPATH . 'uploads/custom-orders', $order['reference_image']);
                }
                $data['reference_image'] = $this->uploader->upload($image, FCPATH . 'uploads/custom-orders');
            }

            $this->customOrderService->update($id, $data);
            $updated = $this->customOrderService->getById($id);

            return $this->respondSuccess($updated, 'Pesanan berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function changeStatus($id = null)
    {
        $order = $this->customOrderService->getById($id);
        if (!$order) {
            return $this->respondNotFound();
        }

        $rules = CustomOrderRules::changeStatus();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);
            $userId = $this->getUserId();

            $updated = $this->customOrderService->changeStatus($id, $data['status'], $userId);

            return $this->respondSuccess($updated, 'Status pesanan berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }
}
