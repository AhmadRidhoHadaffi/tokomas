<?php

namespace App\Controllers\Api\V1;

use App\Services\PromotionService;

class PromotionController extends BaseApiController
{
    protected PromotionService $promotionService;

    public function __construct()
    {
        $this->promotionService = new PromotionService();
    }

    public function index()
    {
        $filters = [
            'status' => $this->request->getGet('status'),
            'sort' => $this->request->getGet('sort'),
            'order' => $this->request->getGet('order'),
            'page' => $this->request->getGet('page'),
            'per_page' => $this->request->getGet('per_page'),
        ];

        $result = $this->promotionService->getAll($filters);
        return $this->respondSuccess($result['items'], '', 200, ['total' => $result['total'], 'page' => $result['page'], 'per_page' => $result['per_page'], 'total_pages' => $result['total_pages']]);
    }

    public function show($id = null)
    {
        $promo = $this->promotionService->getById($id);
        if (!$promo) return $this->respondNotFound();
        return $this->respondSuccess($promo);
    }

    public function create()
    {
        $data = $this->request->getJSON(true);
        $userId = $this->getUserId();
        try {
            $id = $this->promotionService->create($data, $userId);
            $promo = $this->promotionService->getById($id);
            return $this->respondCreated($promo);
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function update($id = null)
    {
        if (!$this->promotionService->getById($id)) return $this->respondNotFound();
        $data = $this->request->getJSON(true);
        try {
            $this->promotionService->update($id, $data);
            return $this->respondSuccess($this->promotionService->getById($id), 'Promosi diperbarui');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function delete($id = null)
    {
        if (!$this->promotionService->getById($id)) return $this->respondNotFound();
        try {
            $this->promotionService->delete($id);
            return $this->respondNoContent('Promosi dihapus');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }

    public function send($id = null)
    {
        try {
            $count = $this->promotionService->send($id);
            return $this->respondSuccess(['sent_count' => $count], 'Promosi berhasil dikirim ke ' . $count . ' pelanggan');
        } catch (\Exception $e) {
            return $this->respondFail($e->getMessage(), 400);
        }
    }
}
