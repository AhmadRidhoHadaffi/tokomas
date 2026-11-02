<?php

namespace App\Services;

use App\Models\PromotionModel;
use App\Models\PromotionCustomerModel;
use App\Models\NotificationModel;
use App\Models\CustomerModel;

class PromotionService
{
    protected PromotionModel $promotionModel;
    protected PromotionCustomerModel $promotionCustomerModel;
    protected NotificationModel $notificationModel;
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->promotionModel = new PromotionModel();
        $this->promotionCustomerModel = new PromotionCustomerModel();
        $this->notificationModel = new NotificationModel();
        $this->customerModel = new CustomerModel();
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->promotionModel->builder();

        if (!empty($filters['status'])) {
            $builder->where('status', $filters['status']);
        }

        $sortField = $filters['sort'] ?? 'created_at';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $promotions = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Add recipient count
        foreach ($promotions as &$promo) {
            if ($promo['target_type'] === 'semua') {
                $promo['recipient_count'] = $this->customerModel->countAllResults();
            } else {
                $promo['recipient_count'] = $this->promotionCustomerModel->where('promotion_id', $promo['id'])->countAllResults();
            }
        }

        return [
            'items' => $promotions,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?array
    {
        $promo = $this->promotionModel->find($id);
        if (!$promo) return null;

        if ($promo['target_type'] === 'semua') {
            $promo['recipient_count'] = $this->customerModel->countAllResults();
        } else {
            $promo['recipient_count'] = $this->promotionCustomerModel->where('promotion_id', $id)->countAllResults();
            $promo['selected_customers'] = $this->promotionCustomerModel->where('promotion_id', $id)->findAll();
        }

        return $promo;
    }

    public function create(array $data, int $userId): int
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $data['user_id'] = $userId;
            $data['status'] = $data['status'] ?? 'Draf';

            $promoId = $this->promotionModel->insert($data);

            if ($data['target_type'] === 'terpilih' && !empty($data['customer_ids'])) {
                foreach ($data['customer_ids'] as $customerId) {
                    $this->promotionCustomerModel->insert([
                        'promotion_id' => $promoId,
                        'customer_id' => $customerId,
                    ]);
                }
            }

            $db->transComplete();
            return $promoId;
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        return $this->promotionModel->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->promotionModel->delete($id);
    }

    public function send(int $id): int
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $promo = $this->promotionModel->find($id);
            if (!$promo) throw new \Exception('Promosi tidak ditemukan');

            if ($promo['status'] === 'Terkirim') {
                throw new \Exception('Promosi sudah terkirim');
            }

            // Get target customers
            if ($promo['target_type'] === 'semua') {
                $customers = $this->customerModel->findAll();
            } else {
                $customerIds = array_column(
                    $this->promotionCustomerModel->where('promotion_id', $id)->findAll(),
                    'customer_id'
                );
                $customers = $this->customerModel->whereIn('id', $customerIds)->findAll();
            }

            // Create notifications
            $count = 0;
            foreach ($customers as $customer) {
                $this->notificationModel->insert([
                    'customer_id' => $customer['id'],
                    'promotion_id' => $id,
                    'type' => 'Promosi',
                    'title' => $promo['title'],
                    'message' => $promo['message'],
                ]);
                $count++;
            }

            // Update promotion status
            $this->promotionModel->update($id, [
                'status' => 'Terkirim',
                'send_at' => date('Y-m-d H:i:s'),
            ]);

            $db->transComplete();
            return $count;
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
