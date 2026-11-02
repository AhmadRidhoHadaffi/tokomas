<?php

namespace Tests\Feature\Api\V1;

use App\Database\Seeds\TestSeeder;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

final class AuthApiTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $seed = TestSeeder::class;
    protected $namespace = 'App';
    protected $basePath = APPPATH . 'Database';

    public function testLoginReturnsJwtForActiveUser(): void
    {
        $result = $this->withBodyFormat('json')->post('api/v1/auth/login', [
            'email' => 'owner@test.local',
            'password' => 'owner123',
        ]);

        $result->assertStatus(200);
        $result->assertJSONFragment(['success' => true]);
        $body = json_decode($result->getJSON(), true, 512, JSON_THROW_ON_ERROR);
        $this->assertSame('owner', $body['data']['user']['role']);
        $this->assertNotEmpty($body['data']['token']);
    }

    public function testInactiveUserCannotLogin(): void
    {
        $result = $this->withBodyFormat('json')->post('api/v1/auth/login', [
            'email' => 'inactive@test.local',
            'password' => 'inactive123',
        ]);

        $result->assertStatus(401);
        $result->assertJSONFragment(['success' => false]);
    }

    public function testRegisterCreatesUserAndCustomer(): void
    {
        $result = $this->withBodyFormat('json')->post('api/v1/auth/register', [
            'name' => 'Pelanggan Baru',
            'email' => 'baru@test.local',
            'phone' => '081200000099',
            'password' => 'rahasia123',
            'address' => 'Alamat Baru',
        ]);

        $result->assertStatus(201);
        $this->seeInDatabase('users', ['email' => 'baru@test.local', 'role' => 'pelanggan']);
        $this->seeInDatabase('customers', ['email' => 'baru@test.local', 'phone' => '081200000099']);
    }
}

