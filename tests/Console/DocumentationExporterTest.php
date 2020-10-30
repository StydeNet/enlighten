<?php

namespace Tests\Console;

use http\Client\Response;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
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

        $this->expectHttpRequest($run->url)
            ->andReturn('Index');

        $this->exporter->export($run);

        $this->assertDocumentHasContent('Index', 'index.html');
        $this->assertDocumentHasContent('Group', 'feature-list-users.html');
        $this->assertDocumentHasContent('Example', 'feature-list-users/lists_users.html');
        $this->assertDocumentHasContent('Example', 'feature-list-users/paginates_users.html');
    }

    private function resetDocumentationDirectory()
    {
        if (! File::isDirectory($this->baseDir)) {
            return;
        }

        File::deleteDirectory($this->baseDir);
    }

    private function assertDocumentHasContent(string $expectedContent, $filename)
    {
        $this->assertFileExists("{$this->baseDir}/{$filename}");
        $this->assertSame($expectedContent, file_get_contents("{$this->baseDir}/$filename"));
    }

    private function expectHttpRequest(string $url)
    {
        Http::shouldReceive('get')
            ->with($url)
            ->andReturn($response = \Mockery::mock(Response::class));

        return $response->shouldReceive('body');
    }
}
