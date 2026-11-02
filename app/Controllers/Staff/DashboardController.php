<?php

namespace App\Controllers\Staff;

use App\Controllers\BaseController;
use App\Services\DashboardService;

class DashboardController extends BaseController
{
    public function index(): string
    {
        return view('pages/staff/dashboard', [
            'title' => 'Dashboard Karyawan',
            'stats' => (new DashboardService())->getKaryawanStats(),
        ]);
    }
}
