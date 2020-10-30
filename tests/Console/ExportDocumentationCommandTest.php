<?php

namespace Tests\Console;

use Mockery;
use Styde\Enlighten\Console\DocumentationExporter;
use Tests\TestCase;

class ExportDocumentationCommandTest extends TestCase
{
    /** @test */
    function exports_a_run()
    {
        $this->createRun('main', 'abcde', true);
        $this->createRun('develop', 'fghij', false);

        $exporterSpy = Mockery::spy(DocumentationExporter::class);
        $this->app->instance(DocumentationExporter::class, $exporterSpy);

        $selectedRun = 'main * abcde';

        $this->artisan('enlighten:export')
            ->expectsChoice("Please select the run you'd like to export", $selectedRun, [
                'develop fghij',
                $selectedRun
            ])
            ->expectsOutput('`main * abcde` run exported!')
            ->assertExitCode(0);

        $exporterSpy->shouldHaveReceived('export', function ($run) use ($selectedRun) {
            return $run->signature === $selectedRun;
        });
    }
}
