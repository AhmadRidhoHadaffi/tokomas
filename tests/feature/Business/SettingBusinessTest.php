<?php

namespace Tests\Feature\Business;

use App\Models\GoldPriceModel;
use App\Models\StoreSettingModel;
use App\Models\UserModel;
use App\Services\SettingService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class SettingBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    public function testStoreSettingsAndGoldPriceCanBeInsertedAndUpdated(): void
    {
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        $db->table('gold_prices')->truncate();
        $db->table('store_settings')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $userId = (new UserModel())->insert(['name' => 'Owner', 'email' => uniqid() . '@test.local', 'password' => password_hash('x', PASSWORD_DEFAULT), 'role' => 'owner', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $service = new SettingService();
        $this->assertTrue($service->updateStoreSettings(['store_name' => 'Gembira', 'email' => 'a@test.local', 'phone' => '0812', 'whatsapp' => '0812', 'address' => 'Jakarta', 'notify_status_change' => 1, 'notify_payment' => 1, 'notify_low_stock' => 1]));
        $this->assertSame('Gembira', $service->getStoreSettings()['store_name']);
        $this->assertTrue($service->updateStoreSettings(['store_name' => 'Gembira Baru']));
        $this->assertSame('Gembira Baru', $service->getStoreSettings()['store_name']);
        $this->assertTrue($service->updateGoldPrice('24K', 1500000, $userId));
        $this->assertTrue($service->updateGoldPrice('24K', 1600000, $userId));
        $this->assertSame(1600000.0, (float) (new GoldPriceModel())->find('24K')['price_per_gram']);
    }
}
