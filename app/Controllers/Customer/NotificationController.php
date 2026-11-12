<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomerService;
use App\Services\NotificationService;

class NotificationController extends BaseController
{
    public function index(): string|\CodeIgniter\HTTP\ResponseInterface
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $filters = [
            'is_read' => null,
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'per_page' => 20,
        ];
        $service = new NotificationService();

        return view('pages/customer/notifications', [
            'title' => 'Notifikasi',
            'result' => $service->getByCustomer((int) $customer['id'], $filters),
            'unreadCount' => $service->getUnreadCount((int) $customer['id']),
        ]);
    }

    public function markRead(int $id)
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $updated = (new NotificationService())->markAsRead($id, (int) $customer['id']);

        return redirect()->to('/pelanggan/notifikasi')->with(
            $updated ? 'success' : 'error',
            $updated ? 'Notifikasi ditandai sudah dibaca' : 'Notifikasi tidak ditemukan'
        );
    }

    public function markAll()
    {
        $customer = $this->customer();
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        (new NotificationService())->markAllAsRead((int) $customer['id']);

        return redirect()->to('/pelanggan/notifikasi')->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    private function customer(): ?array
    {
        return (new CustomerService())->getByUserId((int) $this->userId());
    }
}
