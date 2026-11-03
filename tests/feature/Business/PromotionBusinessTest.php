<?php

namespace Tests\Feature\Business;

use App\Models\CustomerModel;
use App\Models\NotificationModel;
use App\Models\PromotionModel;
use App\Models\UserModel;
use App\Services\PromotionService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class PromotionBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;
    private int $userId;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach (['notifications', 'promotion_customers', 'promotions', 'customers', 'users'] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $this->userId = (new UserModel())->insert(['name' => 'Owner Promo', 'email' => 'promo-' . uniqid() . '@test.local', 'password' => password_hash('secret', PASSWORD_DEFAULT), 'role' => 'owner', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        foreach (['111111111', '222222222'] as $suffix) {
            (new CustomerModel())->insert(['user_id' => null, 'name' => 'Pelanggan ' . $suffix, 'phone' => '08' . $suffix, 'email' => null, 'address' => null, 'created_at' => $now, 'updated_at' => $now]);
        }
    }

    public function testSendToAllCreatesNotificationForEveryCustomer(): void
    {
        $service = new PromotionService();
        $promotionId = $service->create(['title' => 'Promo Test', 'message' => 'Diskon hari ini', 'target_type' => 'semua'], $this->userId);
        $count = $service->send($promotionId);
        $promotion = (new PromotionModel())->find($promotionId);
        $this->assertSame(2, $count);
        $this->assertSame('Terkirim', $promotion['status']);
        $this->assertSame(2, (new NotificationModel())->where('promotion_id', $promotionId)->countAllResults());
    }

    public function testSendingSamePromotionTwiceIsRejected(): void
    {
        $service = new PromotionService();
        $promotionId = $service->create(['title' => 'Promo Test', 'message' => 'Diskon hari ini', 'target_type' => 'semua'], $this->userId);
        $service->send($promotionId);
        $this->expectExceptionMessage('sudah terkirim');
        $service->send($promotionId);
    }
}
