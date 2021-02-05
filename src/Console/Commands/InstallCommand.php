<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $name = 'enlighten:install';

    protected $description = 'Install and Enlighten automatically';

    public function handle()
    {
        $this->publishBuildAndConfigFiles();

        $this->output->newLine();

        if ($this->setupEnlightenInTestCase()) {
            $this->info('Installation complete!');
        } else {
            $this->error('The installer has detected changes in your TestCase class.');
            $this->error('Please setup Enlighten manually with the link below:');
            $this->error('https://github.com/StydeNet/enlighten#manual-setup');
        }

        $this->output->newLine();
        $this->warn('Please remember to create and setup the database for Enlighten and to change the APP_URL env variable if necessary.');
        $this->output->newLine();
        $this->info("After running `php artisan enlighten`, you'll find your documentation by visiting: ".url('/enlighten'));
    }

    private function publishBuildAndConfigFiles(): void
    {
        $this->call('vendor:publish', ['--tag' => 'enlighten']);
    }

    private function setupEnlightenInTestCase()
    {
        $appTestCase = File::get(base_path('tests/TestCase.php'));
        $baseTestCase = File::get(__DIR__.'/stubs/BaseTestCase.php.stub');

        if ($appTestCase != $baseTestCase) {
            return false;
        }

        $enlightenTestCase = File::get(__DIR__ . '/stubs/EnlightenTestCase.php.stub');
        File::put(base_path('tests/TestCase.php'), $enlightenTestCase);

        return true;
    }
}
