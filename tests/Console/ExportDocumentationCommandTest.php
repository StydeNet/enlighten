<?php

namespace Tests\Console;

use Mockery;
use Styde\Enlighten\Console\Commands\ExportDocumentationCommand;
use Styde\Enlighten\Console\DocumentationExporter;
use Tests\TestCase;

class ExportDocumentationCommandTest extends TestCase
{
    private $exporterSpy;

    protected function setUp(): void
    {
        parent::setUp();

        $command = new ExportDocumentationCommand(
            $this->exporterSpy = Mockery::spy(DocumentationExporter::class)
        );

        $this->app->instance(ExportDocumentationCommand::class, $command);
    }

    /** @test */
    function exports_a_run()
    {
        $this->createRun('main', 'abcde', true);
        $this->createRun('develop', 'fghij', false);

        $selectedRun = 'main * abcde';

        $this->artisan('enlighten:export')
            ->expectsChoice("Please select the run you'd like to export", $selectedRun, [
                'develop fghij',
                $selectedRun
            ])
            ->expectsOutput('`main * abcde` run exported!')
            ->assertExitCode(0);

        $this->exporterSpy->shouldHaveReceived('export', function ($run) use ($selectedRun) {
            return $run->signature === $selectedRun;
        });
    }
}
