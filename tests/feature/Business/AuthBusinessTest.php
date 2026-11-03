<?php

namespace Tests\Feature\Business;

use App\Models\UserModel;
use App\Services\AuthService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class AuthBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    public function testRegisterLoginInactiveAndChangePassword(): void
    {
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        $db->table('customers')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $service = new AuthService();
        $result = $service->register(['name' => 'Pelanggan', 'email' => 'auth-' . uniqid() . '@test.local', 'phone' => '0812', 'address' => 'Jakarta', 'password' => 'lama']);
        $userId = $result['user']['id'];
        $this->assertSame('pelanggan', $result['user']['role']);
        $this->assertNotNull($service->login($result['user']['email'], 'lama'));
        $this->assertNull($service->login($result['user']['email'], 'salah'));
        $this->assertTrue($service->changePassword($userId, 'lama', 'baru'));
        $this->assertNotNull($service->login($result['user']['email'], 'baru'));
        (new UserModel())->update($userId, ['is_active' => 0]);
        $this->assertNull($service->login($result['user']['email'], 'baru'));
    }
}
