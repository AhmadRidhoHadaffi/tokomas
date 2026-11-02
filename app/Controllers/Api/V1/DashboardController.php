<?php

namespace App\Controllers\Api\V1;

use App\Services\DashboardService;

class DashboardController extends BaseApiController
{
    protected DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        $role = $this->getUserRole();

        if ($role === 'owner') {
            $stats = $this->dashboardService->getOwnerStats();
        } elseif ($role === 'karyawan') {
            $stats = $this->dashboardService->getKaryawanStats();
        } else {
            return $this->respondForbidden('Akses ditolak');
        }

        return $this->respondSuccess($stats);
    }
}
