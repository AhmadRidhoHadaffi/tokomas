<?php

namespace App\Commands;

use App\Database\Seeds\DemoCleanSeeder;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DemoClean extends BaseCommand
{
    protected $group = 'Demo';
    protected $name = 'demo:clean';
    protected $description = 'Remove demo rows (emails @demo.test, DM-* codes, PD-2026-* orders, [DEMO] titles).';
    protected $usage = 'demo:clean';
    protected $arguments = [];
    protected $options = [];

    public function run(array $params)
    {
        $seeder = \Config\Database::seeder();
        $seeder->call(DemoCleanSeeder::class);

        CLI::write('Demo cleanup finished.', 'green');

        return EXIT_SUCCESS;
    }
}
