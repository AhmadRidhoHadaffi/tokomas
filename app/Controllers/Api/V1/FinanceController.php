<?php

namespace App\Controllers\Api\V1;

use App\Services\FinanceService;
use App\Validation\FinanceRules;

class FinanceController extends BaseApiController
{
    protected FinanceService $financeService;

    public function __construct()
    {
        $this->financeService = new FinanceService();
    }

    public function index()
    {
        $filters = [
            'type' => $this->request->getGet('type'),
            'category' => $this->request->getGet('category'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'search' => $this->request->getGet('search'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->financeService->getAll($filters);

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
        $finance = $this->financeService->getById($id);

        if (!$finance) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($finance);
    }

    public function create()
    {
        $rules = FinanceRules::create();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);
            $userId = $this->getUserId();

            $id = $this->financeService->create($data, $userId);
            $finance = $this->financeService->getById($id);

            return $this->respondCreated($finance, 'Pembukuan berhasil ditambahkan');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function update($id = null)
    {
        $finance = $this->financeService->getById($id);
        if (!$finance) {
            return $this->respondNotFound();
        }

        $rules = FinanceRules::update();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);
            $this->financeService->update($id, $data);
            $updated = $this->financeService->getById($id);

            return $this->respondSuccess($updated, 'Pembukuan berhasil diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function delete($id = null)
    {
        $finance = $this->financeService->getById($id);
        if (!$finance) {
            return $this->respondNotFound();
        }

        try {
            $this->financeService->delete($id);
            return $this->respondNoContent('Pembukuan berhasil dihapus');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function summary()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');

        $summary = $this->financeService->getSummary($startDate, $endDate);

        return $this->respondSuccess($summary);
    }

    public function categories()
    {
        $categories = $this->financeService->getCategories();
        return $this->respondSuccess($categories);
    }
}
