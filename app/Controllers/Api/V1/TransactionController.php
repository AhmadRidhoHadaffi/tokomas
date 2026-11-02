<?php

namespace App\Controllers\Api\V1;

use App\Services\TransactionService;
use App\Validation\TransactionRules;

class TransactionController extends BaseApiController
{
    protected TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    public function index()
    {
        $filters = [
            'search' => $this->request->getGet('search'),
            'payment_status' => $this->request->getGet('payment_status'),
            'payment_method' => $this->request->getGet('payment_method'),
            'start_date' => $this->request->getGet('start_date'),
            'end_date' => $this->request->getGet('end_date'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->transactionService->getAll($filters);

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
        $transaction = $this->transactionService->getById($id);

        if (!$transaction) {
            return $this->respondNotFound();
        }

        return $this->respondSuccess($transaction);
    }

    public function create()
    {
        $rules = TransactionRules::create();

        if (!$this->validate($rules)) {
            return $this->respondValidationError($this->validator->getErrors());
        }

        try {
            $data = $this->request->getJSON(true);
            $userId = $this->getUserId();

            $transaction = $this->transactionService->create($data, $userId);

            return $this->respondCreated($transaction, 'Transaksi berhasil dibuat');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }
}
