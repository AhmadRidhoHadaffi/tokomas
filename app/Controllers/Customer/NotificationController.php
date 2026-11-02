<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomerService;
use App\Services\NotificationService;

class NotificationController extends BaseController
{
    public function index(): string
    {
        $customer = (new CustomerService())->getByUserId($this->userId());
        if (!$customer) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        $filters = ['is_read' => null, 'page' => max(1, (int) ($this->request->getGet('page') ?? 1)), 'per_page' => 20];
        $service = new NotificationService();

        return view('pages/customer/notifications', [
            'title' => 'Notifikasi',
            'result' => $service->getByCustomer($customer['id'], $filters),
            'unreadCount' => $service->getUnreadCount($customer['id']),
        ]);
    }
}
