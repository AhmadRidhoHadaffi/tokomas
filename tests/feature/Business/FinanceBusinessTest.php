<?php

namespace Tests\Feature\Business;

use App\Models\FinanceModel;
use App\Models\UserModel;
use App\Services\FinanceService;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

final class FinanceBusinessTest extends CIUnitTestCase
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
        $db->table('finances')->truncate();
        $db->table('users')->truncate();
        $db->query('SET FOREIGN_KEY_CHECKS=1');
        $now = date('Y-m-d H:i:s');
        $this->userId = (new UserModel())->insert(['name' => 'Owner Test', 'email' => 'owner-' . uniqid() . '@test.local', 'password' => password_hash('secret', PASSWORD_DEFAULT), 'role' => 'owner', 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now], true);
    }

    public function testSummarySeparatesIncomeExpense(): void
    {
        $service = new FinanceService();
        $service->create(['type' => 'Pemasukan', 'category' => 'Lainnya', 'description' => 'Masuk', 'amount' => 500000, 'entry_date' => '2026-11-01'], $this->userId);
        $service->create(['type' => 'Pengeluaran', 'category' => 'Operasional', 'description' => 'Keluar', 'amount' => 125000, 'entry_date' => '2026-11-02'], $this->userId);
        $summary = $service->getSummary('2026-11-01', '2026-11-30');
        $this->assertSame(500000.0, (float) $summary['income']);
        $this->assertSame(125000.0, (float) $summary['expense']);
        $this->assertSame(375000.0, (float) $summary['balance']);
    }

    public function testInvalidCategoryIsRejected(): void
    {
        $this->expectExceptionMessage('Kategori tidak sesuai');
        (new FinanceService())->create(['type' => 'Pemasukan', 'category' => 'Sewa', 'description' => 'Salah', 'amount' => 1], $this->userId);
    }
}
