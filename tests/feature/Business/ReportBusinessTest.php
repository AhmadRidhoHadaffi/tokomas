<?php

namespace Tests\Feature\Business;

use App\Models\CustomOrderModel;
use App\Models\CustomerModel;
use App\Models\FinanceModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Services\ReportService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class ReportBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    private int $ownerId;
    private int $customerId;
    private int $productId;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach (['finances', 'transaction_details', 'transactions', 'custom_order_histories', 'custom_orders', 'customers', 'users', 'products'] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $this->ownerId = (new UserModel())->insert(['name' => 'Owner', 'email' => uniqid() . '@test.local', 'password' => password_hash('x', PASSWORD_DEFAULT), 'role' => 'owner', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $this->customerId = (new CustomerModel())->insert(['user_id' => $this->ownerId, 'name' => 'Customer', 'phone' => '0812', 'email' => uniqid() . '@test.local'], true);
        $this->productId = (new ProductModel())->insert(['name' => 'Cincin', 'description' => 'Test', 'category' => 'Cincin', 'purity' => '24K', 'weight' => 1, 'price' => 1000000, 'stock' => 0, 'image' => null, 'created_at' => $now, 'updated_at' => $now], true);
    }

    public function testReportsAggregateSalesFinanceStockAndOrders(): void
    {
        $transactions = new TransactionModel();
        $transactions->insert(['invoice_no' => 'INV-TEST-1', 'customer_id' => $this->customerId, 'user_id' => $this->ownerId, 'transaction_date' => '2026-11-01 10:00:00', 'discount' => 50000, 'total' => 950000, 'payment_method' => 'Tunai', 'payment_status' => 'Lunas', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        $transactions->insert(['invoice_no' => 'INV-TEST-2', 'customer_id' => $this->customerId, 'user_id' => $this->ownerId, 'transaction_date' => '2026-11-02 10:00:00', 'discount' => 10000, 'total' => 490000, 'payment_method' => 'QRIS', 'payment_status' => 'Menunggu', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]);
        (new FinanceModel())->insert(['user_id' => $this->ownerId, 'type' => 'Pemasukan', 'category' => 'Lainnya', 'description' => 'Masuk', 'amount' => 950000, 'entry_date' => '2026-11-01']);
        (new FinanceModel())->insert(['user_id' => $this->ownerId, 'type' => 'Pengeluaran', 'category' => 'Operasional', 'description' => 'Keluar', 'amount' => 100000, 'entry_date' => '2026-11-02']);
        (new CustomOrderModel())->insert(['order_no' => 'ORD-TEST-1', 'customer_id' => $this->customerId, 'jewelry_type' => 'Cincin', 'model' => 'M1', 'purity' => '24K', 'weight' => 2, 'estimated_price' => 2000000, 'dp' => 500000, 'status' => 'Masuk', 'order_date' => '2026-11-03']);

        $service = new ReportService();
        $sales = $service->salesReport('2026-11-01', '2026-11-30');
        $this->assertSame(2, $sales['summary']['total_transactions']);
        $this->assertSame(950000.0, $sales['summary']['total_sales']);
        $this->assertSame(60000.0, $sales['summary']['total_discount']);
        $finance = $service->financeReport('2026-11-01', '2026-11-30');
        $this->assertSame(950000.0, (float) $finance['summary']['income']);
        $this->assertSame(100000.0, (float) $finance['summary']['expense']);
        $this->assertSame(850000.0, (float) $finance['summary']['balance']);
        $stock = $service->stockReport();
        $this->assertSame(1, $stock['summary']['out_of_stock']);
        $orders = $service->customOrderReport('2026-11-01', '2026-11-30');
        $this->assertSame(1, $orders['summary']['total_orders']);
        $this->assertSame(2000000.0, $orders['summary']['total_estimated_value']);
        $this->assertSame(500000.0, $orders['summary']['total_dp']);
    }
}
