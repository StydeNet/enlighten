<?php

namespace Tests\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Styde\Enlighten\Console\DocumentationExporter;
use Tests\TestCase;

class DocumentationExporterTest extends TestCase
{
    /**
     * @var DocumentationExporter
     */
    private $exporter;

    private $baseDir = 'public/docs';

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->resetDocumentationDirectory();

        $this->exporter = new DocumentationExporter($this->app[Filesystem::class], $this->baseDir);
    }

    /** @test */
    function exports_run_as_static_files()
    {
        $run = $this->createRun('main', 'abcde', true);
        $group = $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest', 'List Users');
        $this->createExample($group, 'lists_users', 'passed', 'Lists users');
        $this->createExample($group, 'paginates_users', 'passed', 'Paginates users');

        $this->exporter->export($run);

        $this->assertFileExists("{$this->baseDir}/index.html");
        $this->assertFileExists("{$this->baseDir}/feature-list-users.html");
        $this->assertFileExists("{$this->baseDir}/feature-list-users/lists_users.html");
        $this->assertFileExists("{$this->baseDir}/feature-list-users/paginates_users.html");
    }

    private function resetDocumentationDirectory()
    {
        if (! File::isDirectory($this->baseDir)) {
            return;
        }

        File::deleteDirectory($this->baseDir);
    }
}
