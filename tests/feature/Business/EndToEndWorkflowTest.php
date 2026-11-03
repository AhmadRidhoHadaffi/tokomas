<?php

namespace Tests\Feature\Business;

use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Services\AuthService;
use App\Services\CustomOrderService;
use App\Services\CustomerService;
use App\Services\FinanceService;
use App\Services\NotificationService;
use App\Services\ReportService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class EndToEndWorkflowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    public function testFullCustomerOrderToReportWorkflow(): void
    {
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach (['notifications', 'finances', 'custom_order_histories', 'custom_orders', 'customers', 'users'] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $staffId = (new UserModel())->insert(['name' => 'Staff', 'email' => uniqid() . '@test.local', 'password' => password_hash('x', PASSWORD_DEFAULT), 'role' => 'karyawan', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $authService = new AuthService();
        $registered = $authService->register(['name' => 'Pelanggan E2E', 'email' => 'e2e-' . uniqid() . '@test.local', 'phone' => '0812', 'address' => 'Jakarta', 'password' => 'rahasia']);
        $userId = $registered['user']['id'];
        $customer = (new CustomerService())->getByUserId($userId);
        $customerId = $customer['id'];
        $orderService = new CustomOrderService();
        $order = $orderService->create(['customer_id' => $customerId, 'jewelry_type' => 'Cincin', 'model' => 'E2E Model', 'purity' => '24K', 'weight' => 3, 'estimated_price' => 3000000, 'dp' => 1000000, 'order_date' => '2026-11-01', 'due_date' => '2026-11-15'], $staffId);
        $orderId = (int) $order['id'];
        $dpFinance = (new FinanceService())->getAll(['custom_order_id' => $orderId]);
        $this->assertCount(1, $dpFinance['items']);
        $this->assertSame(1000000.0, (float) $dpFinance['items'][0]['amount']);
        $orderService->changeStatus($orderId, 'Diproses', $staffId);
        $orderService->changeStatus($orderId, 'Dalam Pembuatan', $staffId);
        $orderService->changeStatus($orderId, 'Selesai', $staffId);
        $orderService->changeStatus($orderId, 'Diambil', $staffId);
        $finalFinance = (new FinanceService())->getAll(['custom_order_id' => $orderId]);
        $this->assertCount(2, $finalFinance['items']);
        $notifs = (new NotificationService())->getByCustomer($customerId);
        $this->assertGreaterThanOrEqual(4, count($notifs['items']));
        $report = (new ReportService())->customOrderReport('2026-11-01', '2026-11-30');
        $this->assertSame(1, $report['summary']['total_orders']);
        $this->assertSame(3000000.0, $report['summary']['total_estimated_value']);
    }
}
