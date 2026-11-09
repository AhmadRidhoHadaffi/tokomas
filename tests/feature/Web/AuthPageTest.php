<?php

namespace Tests\Feature\Web;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

final class AuthPageTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testLoginPageRendersForm(): void
    {
        $result = $this->get('/masuk');
        $result->assertStatus(200);
        $this->assertStringContainsString('Masuk', $result->getBody());
        $this->assertStringContainsString('csrf', $result->getBody());
    }

    public function testProtectedOwnerPageRedirectsGuest(): void
    {
        $result = $this->get('/owner');
        $result->assertRedirectTo('/masuk');
    }

    public function testRoleFilterBlocksWrongRole(): void
    {
        $result = $this->withSession(['user_id' => 3, 'role' => 'pelanggan'])->get('/owner');
        $result->assertStatus(403);
        $result->assertSee('403');
    }

    public function testRoleFilterBlocksCrossRoleAccess(): void
    {
        $cases = [
            ['role' => 'pelanggan', 'path' => '/owner'],
            ['role' => 'pelanggan', 'path' => '/karyawan'],
            ['role' => 'karyawan', 'path' => '/owner'],
            ['role' => 'owner', 'path' => '/karyawan'],
            ['role' => 'owner', 'path' => '/pelanggan'],
            ['role' => 'karyawan', 'path' => '/pelanggan'],
        ];

        foreach ($cases as $case) {
            $result = $this->withSession(['user_id' => 1, 'role' => $case['role']])->get($case['path']);
            $result->assertStatus(403, $case['role'] . ' -> ' . $case['path']);
        }
    }

    public function testGuestFilterRedirectsLoggedInUser(): void
    {
        $result = $this->withSession(['user_id' => 1, 'role' => 'owner'])->get('/masuk');
        $result->assertRedirectTo('/owner');
    }
}
