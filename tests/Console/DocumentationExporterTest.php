<?php

namespace Tests\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;
use Mockery;
use Styde\Enlighten\Console\ContentRequest;
use Styde\Enlighten\Console\DocumentationExporter;
use Tests\TestCase;

class DocumentationExporterTest extends TestCase
{
    /**
     * @var DocumentationExporter
     */
    private $exporter;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var Mockery\MockInterface|ContentRequest
     */
    protected $contentRequest;

    /**
     * @var string
     */
    private $baseDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = $this->app->make(Filesystem::class);

        $this->baseDir = __DIR__.'/public/docs';

        $this->resetDirectory($this->filesystem, $this->baseDir);

        $this->contentRequest = Mockery::mock(ContentRequest::class);

        $this->exporter = new DocumentationExporter(
            $this->filesystem,
            $this->baseDir,
            $this->contentRequest,
            'http://localhost/'
        );
    }

    /** @test */
    function exports_run_as_static_files()
    {
        $run = $this->createRun('main', 'abcde', true);
        $group1 = $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest', 'List Users');
        $example1 = $this->createExample($group1, 'lists_users', 'passed', 'Lists users');
        $example2 = $this->createExample($group1, 'paginates_users', 'passed', 'Paginates users');
        $group2 = $this->createExampleGroup($run, 'Tests\Feature\CreateUserTest', 'Create User');
        $example3 = $this->createExample($group2, 'creates_a_user', 'passed', 'Creates a user');

        $this->expectContentRequest($run->url)->andReturn('Index');
        $this->expectContentRequest($group1->url)->andReturn('Group 1');
        $this->expectContentRequest($example1->url)->andReturn('Example 1');
        $this->expectContentRequest($example2->url)->andReturn('Example 2');
        $this->expectContentRequest($group2->url)->andReturn('Group 2');
        $this->expectContentRequest($example3->url)->andReturn('Example 3');

        $this->exporter->export($run);

        $this->assertDocumentHasContent('Index', 'index.html');
        $this->assertDocumentHasContent('Group 1', 'feature-list-users.html');
        $this->assertDocumentHasContent('Example 1', 'feature-list-users/lists_users.html');
        $this->assertDocumentHasContent('Example 2', 'feature-list-users/paginates_users.html');
        $this->assertDocumentHasContent('Group 2', 'feature-create-user.html');
        $this->assertDocumentHasContent('Example 3', 'feature-create-user/creates_a_user.html');
    }

    private function resetDirectory(Filesystem $filesystem, $dir)
    {
        if (! $filesystem->isDirectory($dir)) {
            return;
        }

        $filesystem->deleteDirectory($dir);
    }

    private function assertDocumentHasContent(string $expectedContent, $filename)
    {
        $this->assertFileExists("{$this->baseDir}/{$filename}");
        $this->assertSame($expectedContent, file_get_contents("{$this->baseDir}/$filename"));
    }

    private function expectContentRequest(string $url)
    {
        return $this->contentRequest->shouldReceive('getContent')->once()->with($url);
    }
}
