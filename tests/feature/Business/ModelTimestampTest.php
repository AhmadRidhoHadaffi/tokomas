<?php

namespace Tests\Feature\Business;

use App\Models\CustomOrderHistoryModel;
use App\Models\CustomOrderModel;
use App\Models\GoldPriceModel;
use App\Models\NotificationModel;
use App\Models\StoreSettingModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Regression tests for the timestamp metadata fixes.
 *
 * Some tables only have a created_at column (notifications,
 * custom_order_histories) while others only have updated_at
 * (store_settings, gold_prices). Models must generate the column that
 * actually exists and must not write to the missing one.
 */
final class ModelTimestampTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    private int $userId;
    private int $customerId;
    private int $orderId;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach ([
            'notifications',
            'custom_order_histories',
            'custom_orders',
            'customers',
            'users',
            'store_settings',
            'gold_prices',
        ] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');

        $now = date('Y-m-d H:i:s');
        $this->userId = (new UserModel())->insert([
            'name' => 'Petugas Timestamp',
            'email' => 'ts-' . uniqid() . '@test.local',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'karyawan',
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);
        $this->customerId = (new CustomerModel())->insert([
            'user_id' => null,
            'name' => 'Pelanggan Timestamp',
            'phone' => '08' . random_int(100000000, 999999999),
            'email' => null,
            'address' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);
        $this->orderId = (new CustomOrderModel())->insert([
            'order_no' => 'TS-' . random_int(100000, 999999),
            'customer_id' => $this->customerId,
            'jewelry_type' => 'Cincin',
            'model' => 'Model Timestamp',
            'purity' => '24K',
            'weight' => 1.5,
            'estimated_price' => 1000000,
            'dp' => 0,
            'status' => 'Masuk',
            'order_date' => date('Y-m-d'),
        ], true);
    }

    private function assertRealDateTime(?string $value): void
    {
        $this->assertNotEmpty($value, 'Timestamp should be populated');
        $this->assertNotSame('0000-00-00 00:00:00', $value);
        $this->assertNotFalse(strtotime((string) $value), 'Timestamp should be parseable');
    }

    public function testNotificationCreatedAtIsPopulatedAndMarkAsReadWorks(): void
    {
        $model = new NotificationModel();
        $id = $model->insert([
            'customer_id' => $this->customerId,
            'type' => 'Promosi',
            'title' => 'Promo Timestamp',
            'message' => 'Pesan promo',
            'is_read' => 0,
        ], true);

        $row = $model->find($id);
        $this->assertRealDateTime($row['created_at']);

        // Updating must not touch a non-existent updated_at column.
        $this->assertTrue($model->markAsRead($id));
        $this->assertSame(1, (int) $model->find($id)['is_read']);
    }

    public function testCustomOrderHistoryCreatedAtIsPopulated(): void
    {
        $model = new CustomOrderHistoryModel();
        $id = $model->insert([
            'custom_order_id' => $this->orderId,
            'status' => 'Masuk',
            'changed_by' => $this->userId,
        ], true);

        $this->assertRealDateTime($model->find($id)['created_at']);
    }

    public function testStoreSettingUpdatedAtIsPopulatedOnInsertAndUpdate(): void
    {
        $model = new StoreSettingModel();
        $id = $model->insert(['store_name' => 'Toko Timestamp'], true);

        $row = $model->find($id);
        $this->assertRealDateTime($row['updated_at']);

        $this->assertTrue($model->update($id, ['store_name' => 'Toko Timestamp Baru']));
        $updated = $model->find($id);
        $this->assertSame('Toko Timestamp Baru', $updated['store_name']);
        $this->assertRealDateTime($updated['updated_at']);
    }

    public function testGoldPriceUpdatedAtIsPopulatedOnInsertAndUpdate(): void
    {
        $model = new GoldPriceModel();
        $model->insert([
            'purity' => '24K',
            'price_per_gram' => 1500000,
            'updated_by' => $this->userId,
        ]);

        $this->assertRealDateTime($model->find('24K')['updated_at']);

        $this->assertTrue($model->update('24K', ['price_per_gram' => 1600000]));
        $updated = $model->find('24K');
        $this->assertSame(1600000.0, (float) $updated['price_per_gram']);
        $this->assertRealDateTime($updated['updated_at']);
    }
}
