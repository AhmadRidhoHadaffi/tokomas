<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\DashboardService;

class DashboardController extends BaseController
{
    public function index(): string
    {
        return view('pages/owner/dashboard/index', [
            'title' => 'Dashboard Owner',
            'stats' => (new DashboardService())->getOwnerStats(),
        ]);
    }
}
