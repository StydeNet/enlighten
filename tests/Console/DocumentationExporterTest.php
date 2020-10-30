<?php

namespace Tests\Console;

use Illuminate\Filesystem\Filesystem;
use Styde\Enlighten\Console\DocumentationExporter;
use Tests\TestCase;

class DocumentationExporterTest extends TestCase
{
    /**
     * @var DocumentationExporter
     */
    private $exporter;

    protected function setUp(): void
    {
        parent::setUp();

        $this->exporter = new DocumentationExporter($this->app[Filesystem::class]);
    }

    /** @test */
    function exports_run_as_static_files()
    {
        $run = $this->createRun('main', 'abcde', true);
        $group = $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest', 'List Users');
        $this->createExample($group, 'lists_users', 'passed', 'Lists users');
        $this->createExample($group, 'paginates_users', 'passed', 'Paginates users');

        $this->exporter->export($run);

        $baseDir = 'public/docs';
        $this->assertFileExists("{$baseDir}/index.html");
        $this->assertFileExists("{$baseDir}/feature-list-users.html");
        $this->assertFileExists("{$baseDir}/feature-list-users/lists_users.html");
        $this->assertFileExists("{$baseDir}/feature-list-users/paginates_users.html");
    }
}
