<?php

namespace App\Services;

use App\Models\CustomOrderModel;
use App\Models\CustomOrderHistoryModel;
use App\Models\CustomerModel;
use App\Models\NotificationModel;
use App\Models\FinanceModel;
use App\Models\StoreSettingModel;

class CustomOrderService
{
    protected CustomOrderModel $orderModel;
    protected CustomOrderHistoryModel $historyModel;
    protected CustomerModel $customerModel;
    protected NotificationModel $notificationModel;
    protected FinanceModel $financeModel;
    protected StoreSettingModel $settingModel;

    protected array $validStatuses = ['Masuk', 'Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'];

    public function __construct()
    {
        $this->orderModel = new CustomOrderModel();
        $this->historyModel = new CustomOrderHistoryModel();
        $this->customerModel = new CustomerModel();
        $this->notificationModel = new NotificationModel();
        $this->financeModel = new FinanceModel();
        $this->settingModel = new StoreSettingModel();
    }

    public function getAll(array $filters = []): array
    {
        $builder = $this->orderModel->builder();
        $builder->select('custom_orders.*, customers.name as customer_name, customers.phone as customer_phone')
            ->join('customers', 'customers.id = custom_orders.customer_id');

        // Search
        if (!empty($filters['search'])) {
            $builder->groupStart()
                ->like('custom_orders.order_no', $filters['search'])
                ->orLike('customers.name', $filters['search'])
                ->orLike('customers.phone', $filters['search'])
                ->groupEnd();
        }

        // Filter by status
        if (!empty($filters['status'])) {
            $builder->where('custom_orders.status', $filters['status']);
        }

        // Filter by customer_id
        if (!empty($filters['customer_id'])) {
            $builder->where('custom_orders.customer_id', $filters['customer_id']);
        }

        // Filter by date range
        if (!empty($filters['start_date'])) {
            $builder->where('custom_orders.order_date >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $builder->where('custom_orders.order_date <=', $filters['end_date']);
        }

        // Sorting
        $sortField = $filters['sort'] ?? 'custom_orders.order_date';
        $sortOrder = $filters['order'] ?? 'DESC';
        $builder->orderBy($sortField, $sortOrder);

        // Pagination
        $page = (int) ($filters['page'] ?? 1);
        $perPage = (int) ($filters['per_page'] ?? 20);
        $offset = ($page - 1) * $perPage;

        $total = $builder->countAllResults(false);
        $orders = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Transform: jewelry_type + model = product
        $orders = array_map([$this, 'transformOrder'], $orders);

        return [
            'items' => $orders,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    public function getById(int $id): ?array
    {
        $order = $this->orderModel->getWithHistory($id);
        return $order ? $this->transformOrder($order) : null;
    }

    public function create(array $data, int $userId): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Validate customer
            $customer = $this->customerModel->find($data['customer_id']);
            if (!$customer) {
                throw new \Exception('Customer tidak ditemukan');
            }

            // Generate order number
            $orderNo = $this->orderModel->generateOrderNo();

            // Insert order
            $orderId = $this->orderModel->insert([
                'order_no' => $orderNo,
                'customer_id' => $data['customer_id'],
                'jewelry_type' => $data['jewelry_type'],
                'model' => $data['model'],
                'engraving_name' => $data['engraving_name'] ?? null,
                'purity' => $data['purity'],
                'weight' => $data['weight'] ?? null,
                'estimated_price' => $data['estimated_price'],
                'dp' => $data['dp'] ?? 0,
                'status' => 'Masuk',
                'order_date' => $data['order_date'] ?? date('Y-m-d'),
                'due_date' => $data['due_date'] ?? null,
                'reference_image' => $data['reference_image'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            if (!$orderId) {
                throw new \Exception('Gagal membuat pesanan');
            }

            // Insert initial history
            $this->historyModel->insert([
                'custom_order_id' => $orderId,
                'status' => 'Masuk',
                'changed_by' => $userId,
            ]);

            // Auto-record DP to finances if > 0
            if (($data['dp'] ?? 0) > 0) {
                $this->financeModel->insert([
                    'user_id' => $userId,
                    'transaction_id' => null,
                    'custom_order_id' => $orderId,
                    'type' => 'Pemasukan',
                    'category' => 'DP Pesanan Custom',
                    'description' => 'DP ' . $orderNo . ' - ' . $customer['name'],
                    'amount' => $data['dp'],
                    'entry_date' => date('Y-m-d'),
                ]);
            }

            // Send notification
            $settings = $this->settingModel->getSettings();
            if ($settings && $settings['notify_status_change']) {
                $this->notificationModel->insert([
                    'customer_id' => $data['customer_id'],
                    'promotion_id' => null,
                    'type' => 'Status Pesanan',
                    'title' => 'Pesanan Custom Diterima',
                    'message' => 'Pesanan ' . $orderNo . ' telah diterima dan akan segera diproses.',
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            return $this->getById($orderId);
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    public function update(int $id, array $data): bool
    {
        $order = $this->orderModel->find($id);
        if (!$order) {
            throw new \Exception('Pesanan tidak ditemukan');
        }

        // Don't allow changing status via update (use changeStatus)
        unset($data['status']);

        return $this->orderModel->update($id, $data);
    }

    public function changeStatus(int $id, string $newStatus, int $userId): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $order = $this->orderModel->find($id);
            if (!$order) {
                throw new \Exception('Pesanan tidak ditemukan');
            }

            // Validate status progression
            $currentIndex = array_search($order['status'], $this->validStatuses);
            $newIndex = array_search($newStatus, $this->validStatuses);

            if ($newIndex === false) {
                throw new \Exception('Status tidak valid');
            }

            if ($newIndex <= $currentIndex) {
                throw new \Exception('Status hanya bisa maju, tidak bisa mundur atau skip');
            }

            if ($newIndex > $currentIndex + 1) {
                throw new \Exception('Tidak bisa skip status');
            }

            // Update status
            $this->orderModel->update($id, ['status' => $newStatus]);

            // Insert history
            $this->historyModel->insert([
                'custom_order_id' => $id,
                'status' => $newStatus,
                'changed_by' => $userId,
            ]);

            // Auto-record pelunasan if status = Diambil
            if ($newStatus === 'Diambil') {
                $remaining = $order['estimated_price'] - $order['dp'];
                if ($remaining > 0) {
                    $customer = $this->customerModel->find($order['customer_id']);
                    $this->financeModel->insert([
                        'user_id' => $userId,
                        'transaction_id' => null,
                        'custom_order_id' => $id,
                        'type' => 'Pemasukan',
                        'category' => 'Pelunasan Custom',
                        'description' => 'Pelunasan ' . $order['order_no'] . ' - ' . ($customer['name'] ?? ''),
                        'amount' => $remaining,
                        'entry_date' => date('Y-m-d'),
                    ]);
                }
            }

            // Send notification
            $settings = $this->settingModel->getSettings();
            if ($settings && $settings['notify_status_change']) {
                $statusMessages = [
                    'Diproses' => 'Pesanan Anda sedang diproses oleh tim kami.',
                    'Dalam Pembuatan' => 'Pesanan Anda sedang dalam tahap pembuatan oleh pengrajin.',
                    'Selesai' => 'Pesanan Anda sudah selesai. Segera ambil di toko kami.',
                    'Diambil' => 'Pesanan Anda telah diambil. Terima kasih!',
                ];

                $this->notificationModel->insert([
                    'customer_id' => $order['customer_id'],
                    'promotion_id' => null,
                    'type' => 'Status Pesanan',
                    'title' => 'Status Pesanan Diperbarui',
                    'message' => $order['order_no'] . ' - ' . ($statusMessages[$newStatus] ?? 'Status berubah menjadi ' . $newStatus),
                ]);
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            return $this->getById($id);
        } catch (\Exception $e) {
            $db->transRollback();
            throw $e;
        }
    }

    protected function transformOrder(array $order): array
    {
        // Combine jewelry_type + model as product
        $order['product'] = $order['jewelry_type'] . ' ' . $order['model'];
        
        // Format image URL
        if ($order['reference_image'] ?? null) {
            $order['reference_image_url'] = base_url('uploads/custom-orders/' . $order['reference_image']);
        } else {
            $order['reference_image_url'] = null;
        }

        return $order;
    }
}
