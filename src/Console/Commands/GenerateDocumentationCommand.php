<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Console\Command;
use Styde\Enlighten\Contracts\RunBuilder;

class GenerateDocumentationCommand extends Command
{
    protected $signature = 'enlighten
        {--parallel : Indicates if the tests should run in parallel}
        {--recreate-databases : Indicates if the test databases should be re-created}';

    protected $description = 'Run the tests and generate the documentation with Enlighten';

    public function __construct()
    {
        parent::__construct();

        $this->ignoreValidationErrors();
    }

    public function handle()
    {
        app(RunBuilder::class)->reset();

        $this->addCustomBootstrapToGlobalArguments();

        $this->runTests();

        $this->printLinks();
    }

    private function addCustomBootstrapToGlobalArguments()
    {
        $_SERVER['argv'] = array_merge(
            array_slice($_SERVER['argv'], 0, 2),
            ['--bootstrap=vendor/styde/enlighten/src/Tests/bootstrap.php'],
            array_slice($_SERVER['argv'], 2)
        );
    }

    private function runTests(): void
    {
        $this->call('test', [
            '--parallel' => $this->option('parallel'),
            '--recreate-databases' => $this->option('recreate-databases')
        ]);
    }

    private function printLinks()
    {

    }
}
