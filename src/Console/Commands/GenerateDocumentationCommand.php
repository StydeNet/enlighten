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
    /**
     * @var RunBuilder
     */
    private RunBuilder $runBuilder;

    public function __construct(RunBuilder $runBuilder)
    {
        parent::__construct();

        $this->runBuilder = $runBuilder;

        $this->ignoreValidationErrors();
    }

    public function handle()
    {
        $this->runBuilder->reset();

        $_SERVER['argv'] = array_merge(
            array_slice($_SERVER['argv'], 0, 2),
            ['--bootstrap=vendor/styde/enlighten/src/Tests/enable-recording.php'],
            array_slice($_SERVER['argv'], 2)
        );

        $this->call('test', [
            '--parallel' => $this->option('parallel'),
            '--recreate-databases' => $this->option('recreate-databases')
        ]);
    }
}
