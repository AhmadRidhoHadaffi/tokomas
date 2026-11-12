<?php

namespace Tests\Feature\Business;

use App\Models\CustomerModel;
use App\Models\ProductModel;
use App\Models\UserModel;
use App\Services\CustomerService;
use App\Services\EmployeeService;
use App\Services\ProductService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class AdminCrudBusinessTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $DBGroup = 'tests';
    protected $migrate = false;
    protected $refresh = false;

    protected function setUp(): void
    {
        parent::setUp();
        $db = db_connect('tests');
        $db->query('SET FOREIGN_KEY_CHECKS=0');
        foreach (['products', 'customers', 'users'] as $table) {
            $db->table($table)->truncate();
        }
        $db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function testProductCreateAndUpdateMapMetalAndKaratToPurity(): void
    {
        $service = new ProductService();

        $id = $service->create([
            'code' => 'TEST-001',
            'name' => 'Gelang Uji',
            'category' => 'Gelang',
            'metal' => 'Perak',
            'karat' => '925',
            'weight' => 3.5,
            'size' => '20',
            'model' => 'Polos',
            'price' => 450000,
            'stock' => 4,
        ]);

        $product = $service->getById($id);
        $this->assertSame('Perak 925', $product['purity']);
        $this->assertSame('Perak', $product['metal']);
        $this->assertSame('925', $product['karat']);
        $this->assertSame('tersedia', $product['stock_level']);
        $this->assertNull($product['image_url']);

        $service->update($id, [
            'code' => 'TEST-001',
            'name' => 'Gelang Uji Emas',
            'category' => 'Gelang',
            'metal' => 'Emas',
            'karat' => '22K',
            'weight' => 3.5,
            'price' => 550000,
            'stock' => 2,
        ]);

        $updated = $service->getById($id);
        $this->assertSame('22K', $updated['purity']);
        $this->assertSame('hampir', $updated['stock_level']);
        $this->assertSame(550000.0, (float) $updated['price']);
    }

    public function testEmployeeServiceHashesPasswordsAndTogglesStatus(): void
    {
        $service = new EmployeeService();
        $id = $service->create([
            'name' => 'Karyawan Uji',
            'email' => 'karyawan-' . uniqid() . '@test.local',
            'password' => 'Gembira#2026',
            'is_active' => 1,
        ]);

        $employee = $service->getById($id);
        $this->assertArrayNotHasKey('password', $employee);
        $this->assertSame(1, (int) $employee['is_active']);

        $stored = (new UserModel())->find($id);
        $this->assertTrue(password_verify('Gembira#2026', $stored['password']));

        $this->assertTrue($service->toggleActive($id));
        $this->assertSame(0, (int) $service->getById($id)['is_active']);
        $this->assertTrue($service->toggleActive($id));
        $this->assertSame(1, (int) $service->getById($id)['is_active']);

        $this->assertTrue($service->resetPassword($id, 'SandiBaru#2026'));
        $stored = (new UserModel())->find($id);
        $this->assertTrue(password_verify('SandiBaru#2026', $stored['password']));
    }

    public function testCustomerUpdateCannotChangeUserId(): void
    {
        $now = date('Y-m-d H:i:s');
        $ownerId = (new UserModel())->insert([
            'name' => 'Owner Uji',
            'email' => 'owner-' . uniqid() . '@test.local',
            'password' => password_hash('secret', PASSWORD_DEFAULT),
            'role' => 'owner',
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);
        $customerId = (new CustomerModel())->insert([
            'user_id' => null,
            'name' => 'Pelanggan Uji',
            'phone' => '08' . random_int(100000000, 999999999),
            'email' => null,
            'address' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ], true);

        $service = new CustomerService();
        $this->assertTrue($service->update($customerId, [
            'user_id' => $ownerId,
            'name' => 'Pelanggan Diubah',
            'phone' => '081234567890',
            'email' => 'pelanggan-' . uniqid() . '@test.local',
            'address' => 'Jl. Uji 1',
        ]));

        $customer = $service->getById($customerId);
        $this->assertSame('Pelanggan Diubah', $customer['name']);
        $this->assertNull($customer['user_id']);
    }
}
