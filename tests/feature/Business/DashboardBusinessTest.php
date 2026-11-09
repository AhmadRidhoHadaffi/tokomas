<?php

namespace Tests\Feature\Business;

use App\Models\CustomOrderModel;
use App\Models\CustomerModel;
use App\Models\FinanceModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Services\DashboardService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Regression tests for the data the owner/staff dashboards read.
 *
 * The views expect total_products, recent_transactions and
 * active_order_list in addition to the original counters.
 */
final class DashboardBusinessTest extends CIUnitTestCase
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
        foreach ([
            'finances',
            'transaction_details',
            'transactions',
            'custom_order_histories',
            'custom_orders',
            'customers',
            'users',
            'products',
        ] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        $now = date('Y-m-d H:i:s');
        $this->userId = (new UserModel())->insert([
            'name' => 'Owner Dashboard',
            'email' => 'dash-' . uniqid() . '@test.local',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'owner',
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);
        $this->customerId = (new CustomerModel())->insert([
            'user_id' => null,
            'name' => 'Pelanggan Dashboard',
            'phone' => '08' . random_int(100000000, 999999999),
            'email' => null,
            'address' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);

        (new ProductModel())->insert([
            'code' => 'DASH-' . random_int(10000, 99999),
            'name' => 'Cincin Dashboard',
            'category' => 'Cincin',
            'purity' => '24K',
            'weight' => 1.5,
            'size' => null,
            'model' => 'Model Dashboard',
            'price' => 1000000,
            'stock' => 2,
            'image' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $transactions = new TransactionModel();
        $transactions->insert([
            'invoice_no' => 'INV-DASH-LUNAS',
            'customer_id' => $this->customerId,
            'user_id' => $this->userId,
            'transaction_date' => $now,
            'discount' => 0,
            'total' => 500000,
            'payment_method' => 'Tunai',
            'payment_status' => 'Lunas',
        ]);
        $transactions->insert([
            'invoice_no' => 'INV-DASH-MENUNGGU',
            'customer_id' => $this->customerId,
            'user_id' => $this->userId,
            'transaction_date' => $now,
            'discount' => 0,
            'total' => 300000,
            'payment_method' => 'QRIS',
            'payment_status' => 'Menunggu',
        ]);

        $orders = new CustomOrderModel();
        $orders->insert([
            'order_no' => 'ORD-DASH-ACTIVE',
            'customer_id' => $this->customerId,
            'jewelry_type' => 'Cincin',
            'model' => 'Aktif',
            'purity' => '24K',
            'weight' => 2,
            'estimated_price' => 2000000,
            'dp' => 0,
            'status' => 'Masuk',
            'order_date' => date('Y-m-d'),
        ]);
        $orders->insert([
            'order_no' => 'ORD-DASH-DONE',
            'customer_id' => $this->customerId,
            'jewelry_type' => 'Kalung',
            'model' => 'Selesai',
            'purity' => '24K',
            'weight' => 2,
            'estimated_price' => 2000000,
            'dp' => 0,
            'status' => 'Diambil',
            'order_date' => date('Y-m-d'),
        ]);

        $finances = new FinanceModel();
        $finances->insert([
            'user_id' => $this->userId,
            'type' => 'Pemasukan',
            'category' => 'Lainnya',
            'description' => 'Masuk dashboard',
            'amount' => 500000,
            'entry_date' => date('Y-m-d'),
        ]);
        $finances->insert([
            'user_id' => $this->userId,
            'type' => 'Pengeluaran',
            'category' => 'Operasional',
            'description' => 'Keluar dashboard',
            'amount' => 100000,
            'entry_date' => date('Y-m-d'),
        ]);
    }

    public function testOwnerStatsExposeDashboardListsAndCounters(): void
    {
        $stats = (new DashboardService())->getOwnerStats();

        $this->assertSame(500000.0, (float) $stats['sales_today']);
        $this->assertSame(500000.0, (float) $stats['sales_this_month']);
        $this->assertSame(2, (int) $stats['transactions_today']);
        $this->assertSame(1, (int) $stats['active_custom_orders']);
        $this->assertSame(1, (int) $stats['low_stock_count']);
        $this->assertSame(1, (int) $stats['total_products']);

        $this->assertCount(2, $stats['recent_transactions']);
        $this->assertSame('INV-DASH-LUNAS', $stats['recent_transactions'][0]['invoice_no']);
        $this->assertSame('Pelanggan Dashboard', $stats['recent_transactions'][0]['customer_name']);

        $this->assertCount(1, $stats['active_order_list']);
        $this->assertSame('ORD-DASH-ACTIVE', $stats['active_order_list'][0]['order_no']);
        $this->assertSame('Pelanggan Dashboard', $stats['active_order_list'][0]['customer_name']);

        $this->assertSame(500000.0, (float) $stats['finance_summary']['income']);
        $this->assertSame(100000.0, (float) $stats['finance_summary']['expense']);
        $this->assertSame(400000.0, (float) $stats['finance_summary']['balance']);
    }

    public function testKaryawanStatsUseTheSameCounters(): void
    {
        $stats = (new DashboardService())->getKaryawanStats();

        $this->assertSame(500000.0, (float) $stats['sales_today']);
        $this->assertSame(2, (int) $stats['transactions_today']);
        $this->assertSame(1, (int) $stats['active_custom_orders']);
        $this->assertSame(1, (int) $stats['low_stock_count']);
    }
}
