<?php

namespace Tests\Feature\Business;

use App\Models\CustomerModel;
use App\Models\NotificationModel;
use App\Models\UserModel;
use App\Services\CustomerService;
use App\Services\NotificationService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class CustomerOwnershipTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    public function testCustomerCannotAccessOtherCustomerNotifications(): void
    {
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        $db->table('notifications')->truncate();
        $db->table('customers')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $user1 = (new UserModel())->insert(['name' => 'C1', 'email' => uniqid() . '@test.local', 'password' => password_hash('x', PASSWORD_DEFAULT), 'role' => 'pelanggan', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $user2 = (new UserModel())->insert(['name' => 'C2', 'email' => uniqid() . '@test.local', 'password' => password_hash('x', PASSWORD_DEFAULT), 'role' => 'pelanggan', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $cust1 = (new CustomerModel())->insert(['user_id' => $user1, 'name' => 'C1', 'phone' => '0811', 'email' => uniqid() . '@test.local'], true);
        $cust2 = (new CustomerModel())->insert(['user_id' => $user2, 'name' => 'C2', 'phone' => '0812', 'email' => uniqid() . '@test.local'], true);
        $notifId = (new NotificationModel())->insert(['customer_id' => $cust1, 'type' => 'Promosi', 'title' => 'Promo', 'message' => 'Test', 'is_read' => 0], true);
        $service = new NotificationService();
        $this->assertFalse($service->markAsRead($notifId, $cust2));
        $this->assertTrue($service->markAsRead($notifId, $cust1));
    }

    public function testGetByUserIdReturnsOwnedCustomer(): void
    {
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        $db->table('customers')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $userId = (new UserModel())->insert(['name' => 'Pelanggan', 'email' => uniqid() . '@test.local', 'password' => password_hash('x', PASSWORD_DEFAULT), 'role' => 'pelanggan', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
        $custId = (new CustomerModel())->insert(['user_id' => $userId, 'name' => 'Pelanggan', 'phone' => '0812', 'email' => uniqid() . '@test.local'], true);
        $service = new CustomerService();
        $customer = $service->getByUserId($userId);
        $this->assertNotNull($customer);
        $this->assertSame($custId, (int) $customer['id']);
    }
}
