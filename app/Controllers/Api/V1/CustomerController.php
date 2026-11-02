<?php

namespace App\Controllers\Api\V1;

use App\Services\CustomerService;

class CustomerController extends BaseApiController
{
    protected CustomerService $customerService;

    public function __construct()
    {
        $this->customerService = new CustomerService();
    }

    public function index()
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->customerService->getAll($filters);

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
        $customer = $this->customerService->getById($id);

        if (!$customer) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($customer);
    }

    public function update($id = null)
    {
        $customer = $this->customerService->getById($id);
        if (!$customer) {
            return $this->respondNotFound();
        }

        $data = $this->request->getJSON(true);

        try {
            $this->customerService->update($id, $data);
            $updated = $this->customerService->getById($id);

            return $this->respondSuccess($updated, 'Data pelanggan berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }
}
