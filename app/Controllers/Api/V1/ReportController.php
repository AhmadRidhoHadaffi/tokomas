<?php

namespace App\Controllers\Api\V1;

use App\Services\ReportService;
use App\Libraries\CsvExporter;

class ReportController extends BaseApiController
{
    protected ReportService $reportService;
    protected CsvExporter $csvExporter;

    public function __construct()
    {
        $this->reportService = new ReportService();
        $this->csvExporter = new CsvExporter();
    }

    public function sales()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $export = $this->request->getGet('export');

        if ($startDate > $endDate) {
            return $this->respondFail('Tanggal mulai tidak boleh lebih besar dari tanggal selesai', 400);
        }

        $report = $this->reportService->salesReport($startDate, $endDate);

        if ($export === 'csv') {
            $headers = ['Invoice', 'Tanggal', 'Customer', 'Kasir', 'Diskon', 'Total', 'Metode', 'Status'];
            $data = array_map(fn($t) => [$t['invoice_no'], $t['transaction_date'], $t['customer_name'], $t['cashier_name'], $t['discount'], $t['total'], $t['payment_method'], $t['payment_status']], $report['details']);
            $this->csvExporter->export($data, $headers, 'laporan-penjualan-' . date('Ymd') . '.csv');
        }

        return $this->respondSuccess($report);
    }

    public function finances()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $export = $this->request->getGet('export');

        if ($startDate > $endDate) {
            return $this->respondFail('Tanggal mulai tidak boleh lebih besar dari tanggal selesai', 400);
        }

        $report = $this->reportService->financeReport($startDate, $endDate);

        if ($export === 'csv') {
            $headers = ['Tanggal', 'Tipe', 'Kategori', 'Deskripsi', 'Jumlah', 'Dicatat Oleh'];
            $data = array_map(fn($f) => [$f['entry_date'], $f['type'], $f['category'], $f['description'], $f['amount'], $f['recorded_by_name']], $report['details']);
            $this->csvExporter->export($data, $headers, 'laporan-pembukuan-' . date('Ymd') . '.csv');
        }

        return $this->respondSuccess($report);
    }

    public function stock()
    {
        $export = $this->request->getGet('export');
        $report = $this->reportService->stockReport();

        if ($export === 'csv') {
            $headers = ['Kode', 'Nama', 'Kategori', 'Kadar', 'Berat', 'Harga', 'Stok'];
            $data = array_map(fn($p) => [$p['code'], $p['name'], $p['category'], $p['purity'], $p['weight'], $p['price'], $p['stock']], $report['details']);
            $this->csvExporter->export($data, $headers, 'laporan-stok-' . date('Ymd') . '.csv');
        }

        return $this->respondSuccess($report);
    }

    public function customOrders()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?? date('Y-m-d');
        $export = $this->request->getGet('export');

        if ($startDate > $endDate) {
            return $this->respondFail('Tanggal mulai tidak boleh lebih besar dari tanggal selesai', 400);
        }

        $report = $this->reportService->customOrderReport($startDate, $endDate);

        if ($export === 'csv') {
            $headers = ['No Pesanan', 'Customer', 'Telp', 'Jenis', 'Model', 'Kadar', 'Est Harga', 'DP', 'Status', 'Tgl Pesan'];
            $data = array_map(fn($o) => [$o['order_no'], $o['customer_name'], $o['customer_phone'], $o['jewelry_type'], $o['model'], $o['purity'], $o['estimated_price'], $o['dp'], $o['status'], $o['order_date']], $report['details']);
            $this->csvExporter->export($data, $headers, 'laporan-pesanan-custom-' . date('Ymd') . '.csv');
        }

        return $this->respondSuccess($report);
    }
}
