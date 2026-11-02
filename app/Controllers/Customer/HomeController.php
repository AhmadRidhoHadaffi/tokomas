<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Services\CustomerService;
use App\Services\CustomOrderService;

class HomeController extends BaseController
{
    public function index(): string
    {
        $customer = (new CustomerService())->getByUserId($this->userId());
        $activeOrders = $customer ? (new CustomOrderService())->getAll(['customer_id' => $customer['id'], 'per_page' => 5])['items'] : [];

        return view('pages/customer/home', ['title' => 'Beranda', 'customer' => $customer, 'activeOrders' => $activeOrders]);
    }
}
