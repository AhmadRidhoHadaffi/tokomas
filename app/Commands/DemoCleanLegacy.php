<?php

namespace App\Commands;

use App\Database\Seeds\LegacyDemoCleanSeeder;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DemoCleanLegacy extends BaseCommand
{
    protected $group = 'Demo';
    protected $name = 'demo:clean-legacy';
    protected $description = 'Remove the legacy @tokomasgembira.id demo rows without touching @demo.test data.';
    protected $usage = 'demo:clean-legacy';
    protected $arguments = [];
    protected $options = [];

    public function run(array $params)
    {
        $seeder = \Config\Database::seeder();
        $seeder->call(LegacyDemoCleanSeeder::class);

        CLI::write('Legacy demo cleanup finished.', 'green');

        return EXIT_SUCCESS;
    }
}
