<?php

namespace App\Controllers\Owner;

use App\Controllers\BaseController;
use App\Services\ReportService;

class ReportController extends BaseController
{
    public function index(): string
    {
        $start = $this->request->getGet('start_date') ?: date('Y-m-01');
        $end = $this->request->getGet('end_date') ?: date('Y-m-d');
        $service = new ReportService();
        return view('pages/owner/reports/index', ['title' => 'Laporan', 'startDate' => $start, 'endDate' => $end, 'sales' => $service->salesReport($start, $end), 'finances' => $service->financeReport($start, $end), 'stock' => $service->stockReport(), 'customOrders' => $service->customOrderReport($start, $end)]);
    }
}
