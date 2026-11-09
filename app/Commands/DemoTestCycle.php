<?php

namespace App\Commands;

use App\Database\Seeds\DemoCleanSeeder;
use App\Database\Seeds\DemoSeeder;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

/**
 * Proves that DemoSeeder is repeatable and DemoCleanSeeder removes exactly the
 * demo rows. It refuses to run outside a database whose name ends in "_test".
 */
class DemoTestCycle extends BaseCommand
{
    protected $group = 'Demo';
    protected $name = 'demo:test-cycle';
    protected $description = 'Seed + clean demo data on the test database and compare non-demo row counts.';
    protected $usage = 'demo:test-cycle';
    protected $arguments = [];
    protected $options = [];

    private const TABLES = [
        'users', 'customers', 'products', 'transactions', 'transaction_details',
        'custom_orders', 'custom_order_histories', 'finances', 'promotions',
        'promotion_customers', 'notifications',
    ];

    public function run(array $params)
    {
        $db = Database::connect('tests');
        $database = $db->getDatabase();

        if (! str_ends_with($database, '_test')) {
            CLI::error('Refusing to run: tests connection points to ' . $database);

            return EXIT_ERROR;
        }

        $reference = [];
        foreach (['store_settings', 'gold_prices'] as $table) {
            $reference[$table] = $db->table($table)->get()->getResultArray();
        }

        $before = $this->counts($db);

        ob_start();
        (new DemoSeeder(config('Database'), $db))->run();
        ob_end_clean();

        $seeded = $this->demoCounts($db);
        $this->report('After demo seed', $seeded, [
            'users' => 16, 'customers' => 15, 'products' => 36, 'transactions' => 80,
            'custom_orders' => 18, 'promotions' => 7,
        ]);

        ob_start();
        (new DemoCleanSeeder(config('Database'), $db))->run();
        ob_end_clean();

        $this->restoreReference($db, $reference);

        $cleaned = $this->demoCounts($db);
        $after = $this->counts($db);

        $this->report('After demo clean', $cleaned, array_fill_keys(array_keys($cleaned), 0));

        $changed = [];
        foreach (self::TABLES as $table) {
            if (($before[$table] ?? 0) !== ($after[$table] ?? 0)) {
                $changed[] = $table . ': ' . $before[$table] . ' -> ' . $after[$table];
            }
        }

        CLI::newLine();
        $demoRemaining = array_sum($cleaned);
        $ok = $demoRemaining === 0 && $changed === [];

        CLI::write('Non-demo row counts: ' . ($changed === [] ? 'unchanged' : 'CHANGED'), $ok ? 'green' : 'red');
        foreach ($changed as $line) {
            CLI::write('  ' . $line, 'red');
        }

        if (! $ok) {
            CLI::error('Demo cleanup cycle FAILED.');

            return EXIT_ERROR;
        }

        CLI::write('Demo cleanup cycle passed on ' . $database . '.', 'green');

        return EXIT_SUCCESS;
    }

    /**
     * @return array<string, int>
     */
    private function counts(object $db): array
    {
        $counts = [];
        foreach (self::TABLES as $table) {
            $counts[$table] = $db->table($table)->countAllResults();
        }

        return $counts;
    }

    /**
     * @return array<string, int>
     */
    private function demoCounts(object $db): array
    {
        return [
            'users' => $db->table('users')->like('email', '@demo.test', 'before')->countAllResults(),
            'customers' => $db->table('customers')->like('email', '@demo.test', 'before')->countAllResults(),
            'products' => $db->table('products')->like('code', 'DM-', 'after')->countAllResults(),
            'transactions' => $db->table('transactions')->like('invoice_no', 'DM-', 'after')->countAllResults(),
            'custom_orders' => $db->table('custom_orders')->like('order_no', 'PD-2026-', 'after')->countAllResults(),
            'promotions' => $db->table('promotions')->like('title', '[DEMO]', 'after')->countAllResults(),
        ];
    }

    /**
     * @param array<string, int> $counts
     * @param array<string, int> $expected
     */
    private function report(string $title, array $counts, array $expected): void
    {
        CLI::newLine();
        CLI::write('== ' . $title . ' ==', 'yellow');

        foreach ($counts as $label => $count) {
            $match = (int) $expected[$label] === $count;
            CLI::write(sprintf('  %-16s %d %s', $label, $count, $match ? 'OK' : 'MISMATCH'), $match ? 'green' : 'red');
        }
    }

    /**
     * @param array<string, array<int, array<string, mixed>>> $reference
     */
    private function restoreReference(object $db, array $reference): void
    {
        foreach (['gold_prices', 'store_settings'] as $table) {
            $db->table($table)->emptyTable();
            foreach ($reference[$table] as $row) {
                $db->table($table)->insert($row);
            }
        }
    }
}
