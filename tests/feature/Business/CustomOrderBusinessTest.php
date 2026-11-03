<?php

namespace Tests\Feature\Business;

use App\Models\CustomerModel;
use App\Models\CustomOrderHistoryModel;
use App\Models\CustomOrderModel;
use App\Models\FinanceModel;
use App\Models\NotificationModel;
use App\Models\StoreSettingModel;
use App\Models\UserModel;
use App\Services\CustomOrderService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class CustomOrderBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    private int $userId;
    private int $customerId;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach (['finances', 'notifications', 'custom_order_histories', 'custom_orders', 'customers', 'users', 'store_settings'] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $this->userId = (new UserModel())->insert(['name' => 'Karyawan Test', 'email' => 'staff-' . uniqid() . '@test.local', 'password' => password_hash('secret', PASSWORD_DEFAULT), 'role' => 'karyawan', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $this->customerId = (new CustomerModel())->insert(['user_id' => null, 'name' => 'Pelanggan Order', 'phone' => '08' . random_int(100000000, 999999999), 'email' => null, 'address' => null, 'created_at' => $now, 'updated_at' => $now], true);
        (new StoreSettingModel())->insert(['store_name' => 'Toko Test', 'notify_status_change' => 1, 'notify_payment' => 1, 'notify_low_stock' => 1, 'updated_at' => $now]);
    }

    private function createOrder(): array
    {
        return (new CustomOrderService())->create(['customer_id' => $this->customerId, 'jewelry_type' => 'Cincin', 'model' => 'Model Test', 'purity' => '24K', 'weight' => 2.5, 'estimated_price' => 3000000, 'dp' => 1000000], $this->userId);
    }

    public function testCreateRecordsHistoryDpAndNotification(): void
    {
        $order = $this->createOrder();
        $this->assertSame('Masuk', $order['status']);
        $this->assertCount(1, (new CustomOrderHistoryModel())->where('custom_order_id', $order['id'])->findAll());
        $this->assertSame(1, (new FinanceModel())->where('custom_order_id', $order['id'])->countAllResults());
        $this->assertSame(1, (new NotificationModel())->where('customer_id', $this->customerId)->countAllResults());
    }

    public function testStatusCannotSkipAndRecordsProgression(): void
    {
        $order = $this->createOrder();
        $service = new CustomOrderService();
        $this->expectExceptionMessage('Tidak bisa skip status');
        $service->changeStatus($order['id'], 'Dalam Pembuatan', $this->userId);
    }

    public function testStatusProgressionRecordsHistoryAndFinalPayment(): void
    {
        $order = $this->createOrder();
        $service = new CustomOrderService();
        foreach (['Diproses', 'Dalam Pembuatan', 'Selesai', 'Diambil'] as $status) {
            $service->changeStatus($order['id'], $status, $this->userId);
        }
        $updated = (new CustomOrderModel())->find($order['id']);
        $this->assertSame('Diambil', $updated['status']);
        $this->assertSame(5, (new CustomOrderHistoryModel())->where('custom_order_id', $order['id'])->countAllResults());
        $this->assertSame(2, (new FinanceModel())->where('custom_order_id', $order['id'])->countAllResults());
    }
}

