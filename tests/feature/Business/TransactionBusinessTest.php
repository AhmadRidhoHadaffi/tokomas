<?php

namespace Tests\Feature\Business;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\UserModel;
use App\Services\TransactionService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class TransactionBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    private int $userId;
    private int $customerId;
    private int $productId;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach (['finances', 'transaction_details', 'transactions', 'customers', 'users', 'products'] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $this->userId = (new UserModel())->insert(['name' => 'Kasir Test', 'email' => 'kasir-' . uniqid() . '@test.local', 'password' => password_hash('secret', PASSWORD_DEFAULT), 'role' => 'karyawan', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $this->customerId = (new CustomerModel())->insert(['user_id' => null, 'name' => 'Pelanggan Test', 'phone' => '08' . random_int(100000000, 999999999), 'email' => null, 'address' => null, 'created_at' => $now, 'updated_at' => $now], true);
        $this->productId = (new ProductModel())->insert(['code' => 'T' . random_int(10000000, 99999999), 'name' => 'Cincin Test', 'category' => 'Cincin', 'purity' => '24K', 'weight' => 1.25, 'size' => null, 'model' => 'Model Test', 'price' => 1000000, 'stock' => 2, 'image' => null, 'created_at' => $now, 'updated_at' => $now], true);
    }

    public function testCreateCalculatesTotalAndDecreasesStock(): void
    {
        $transaction = (new TransactionService())->create(['customer_id' => $this->customerId, 'items' => [['product_id' => $this->productId, 'quantity' => 2]], 'discount' => 100000, 'payment_method' => 'Tunai', 'payment_status' => 'Lunas'], $this->userId);
        $this->assertSame(1900000.0, (float) $transaction['total']);
        $this->assertSame(0, (int) (new ProductModel())->find($this->productId)['stock']);
    }

    public function testInsufficientStockRollsBackTransaction(): void
    {
        try {
            (new TransactionService())->create(['customer_id' => $this->customerId, 'items' => [['product_id' => $this->productId, 'quantity' => 3]], 'payment_method' => 'Tunai', 'payment_status' => 'Lunas'], $this->userId);
            $this->fail('Expected insufficient stock exception');
        } catch (\Exception $exception) {
            $this->assertStringContainsString('Stok', $exception->getMessage());
        }
        $this->assertSame(2, (int) (new ProductModel())->find($this->productId)['stock']);
        $this->assertSame(0, (new TransactionModel())->countAllResults());
    }
}

