<?php

namespace Tests\Feature\Web;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

final class LandingPageTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $migrateOnce = true;
    protected $refresh = false;

    public function testLandingRendersHtml(): void
    {
        $result = $this->get('/');
        $result->assertStatus(200);
        $result->assertSee('Toko Mas Gembira');
    }
}
