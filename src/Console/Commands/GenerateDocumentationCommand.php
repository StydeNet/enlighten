<?php

namespace Styde\Enlighten\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Styde\Enlighten\Contracts\Run as RunContract;
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
        $runBuilder = app(RunBuilder::class);

        $runBuilder->reset();

        $this->addCustomBootstrapToGlobalArguments();

        $this->runTests();

        $run = $runBuilder->getRun();

        if ($run->isEmpty()) {
            $this->printMissingSetupWarning();
        } else {
            $this->printFailedExamples($run);
            $this->printDocumentationLink($run);
            $this->openOnBrowser($run);
        }
    }

    private function openOnBrowser(RunContract $run)
    {
        exec("open {$run->url()}");
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

    private function printMissingSetupWarning()
    {
        $this->output->newLine();
        $this->alert('The documentation was not generated');
        $this->output->newLine();
        $this->error('Did you forget to call `$this->setUpEnlighten();` in your tests?');
        $this->warn('Learn more: https://github.com/StydeNet/enlighten#installation');
    }

    private function printFailedExamples(RunContract $run)
    {
        $failedExamples = $run->getFailedExamples();

        if ($failedExamples->isNotEmpty()) {
            $this->printFailedExamplesHeader($failedExamples);
            $this->printFailedExampleItems($failedExamples);
        }
    }

    private function printFailedExamplesHeader($examples)
    {
        $this->output->newLine();
        $this->error(sprintf(
            '⚠️  %s %s failed:',
            $examples->count(),
            Str::plural('test', $examples->count())
        ));
        $this->output->newLine();
    }

    private function printFailedExampleItems($examples)
    {
        $examples->each(function ($example) {
            $this->line("❌ {$example->getTitle()}:");
            $this->warn($example->getUrl());
            $this->output->newLine();
        });
    }

    private function printDocumentationLink(RunContract $run)
    {
        $this->line('⚡ Check your documentation at:');
        $this->info($run->url());
    }
}
