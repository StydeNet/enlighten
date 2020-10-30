<?php

namespace Tests\Console;

use Tests\TestCase;

class ExportDocumentationCommandTest extends TestCase
{
    /** @test */
    function exports_documentation_as_static_files()
    {
        $this->createRun('main', 'abcde', true);
        $this->createRun('develop', 'fghij', false);

        $this->artisan('enlighten:export')
            ->expectsChoice("Please select the run you'd like to export", 'main * abcde', [
                'develop fghij',
                'main * abcde'
            ])
            ->expectsOutput('main * abcde')
            ->assertExitCode(0);
    }
}
