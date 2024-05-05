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

    public function handle(): void
    {
        $runBuilder = app(RunBuilder::class);

        $runBuilder->reset();
        $runBuilder->save();

        $this->addCustomBootstrapToGlobalArguments();

        $this->runTests();

        $run = $runBuilder->getRun();

        if ($run->isEmpty()) {
            $this->printMissingSetupWarning();
        } else {
            $this->printFailedExamples($run);
            $this->printDocumentationLink($run);
        }
    }

    private function addCustomBootstrapToGlobalArguments(): void
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

    private function printMissingSetupWarning(): void
    {
        $this->output->newLine();
        $this->alert('The documentation was not generated');
        $this->output->newLine();
        $this->error('Did you forget to call `$this->setUpEnlighten();` in your tests?');
        $this->warn('Learn more: https://github.com/StydeNet/enlighten#installation');
    }

    private function printFailedExamples(RunContract $run): void
    {
        $failedExamples = $run->getFailedExamples();

        if ($failedExamples->isNotEmpty()) {
            $this->printFailedExamplesHeader($failedExamples);
            $this->printFailedExampleItems($failedExamples);
        }
    }

    private function printFailedExamplesHeader($examples): void
    {
        $this->output->newLine();
        $this->error(sprintf(
            '⚠️  %s %s failed:',
            $examples->count(),
            Str::plural('test', $examples->count())
        ));
    }

    private function printFailedExampleItems($examples): void
    {
        $examples->each(function ($example) {
            $this->output->newLine();
            $this->line("❌ {$example->getTitle()}:");
            $this->warn($example->getUrl());
        });
    }

    private function printDocumentationLink(RunContract $run): void
    {
        $this->output->newLine();
        $this->line('⚡ Check your documentation at:');
        $this->info($run->url());
    }
}
