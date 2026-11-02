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

    public function testGuestFilterRedirectsLoggedInUser(): void
    {
        $result = $this->withSession(['user_id' => 1, 'role' => 'owner'])->get('/masuk');
        $result->assertRedirectTo('/owner');
    }
}
