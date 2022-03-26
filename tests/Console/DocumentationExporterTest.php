<?php

namespace Tests\Console;

use Illuminate\Filesystem\Filesystem;
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

        $this->exporter = new DocumentationExporter($this->filesystem, $this->contentRequest);

        $this->setConfig([
            'enlighten.areas' => [
                'api' => 'API',
            ],
        ]);
    }

    /** @test */
    function exports_run_as_static_files()
    {
        config(['enlighten.theme.dist' => __DIR__.'/../../vendor/styde/enlighten-base-template/dist']);

        $run = $this->createRun('main', 'abcde', true);
        $group1 = $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest', 'List Users');
        $example1 = $this->createExample($group1, 'lists_users', 'passed', 'Lists Users');
        $example2 = $this->createExample($group1, 'paginates_users', 'passed', 'Paginates Users');
        $group2 = $this->createExampleGroup($run, 'Tests\Api\CreateUserTest', 'Create User');
        $example3 = $this->createExample($group2, 'creates_a_user', 'passed', 'Creates a User');

        $anotherRun = $this->createRun('main', 'defghj', true);
        $anotherGroup = $this->createExampleGroup($anotherRun, 'Tests\Feature\ListUsersTest', 'List Users');
        $anotherExample = $this->createExample($anotherGroup, 'lists_users', 'passed', 'Lists Users');

        $this->expectContentRequest($run->url)->andReturn('Index');
        $this->expectContentRequest($run->areaUrl('feature'))->andReturn('Feature');
        $this->expectContentRequest($run->areaUrl('api'))->andReturn('Api');
        $this->expectContentRequest($group1->url)->andReturn('Group 1');
        $this->expectContentRequest($example1->url)->andReturn('Example 1');
        $this->expectContentRequest($example2->url)->andReturn('Example 2');
        $this->expectContentRequest($group2->url)->andReturn('Group 2');
        $this->expectContentRequest($example3->url)->andReturn('Example 3');

        $this->exporter->export($run, __DIR__.'/public/docs', '/docs');

        $this->assertDirectoryExists(__DIR__.'/public/docs/assets');
        $this->assertFileExists(__DIR__.'/public/docs/assets/css/app.css');

        $this->assertDocumentHasContent('Index', 'index.html');
        $this->assertDocumentHasContent('Feature', 'areas/feature.html');
        $this->assertDocumentHasContent('Group 1', 'feature-list-users.html');
        $this->assertDocumentHasContent('Example 1', 'feature-list-users/lists-users.html');
        $this->assertDocumentHasContent('Example 2', 'feature-list-users/paginates-users.html');
        $this->assertDocumentHasContent('Group 2', 'api-create-user.html');
        $this->assertDocumentHasContent('Example 3', 'api-create-user/creates-a-user.html');


        $this->assertFileExists(__DIR__.'/public/docs/search.json');

        $expectedJson = [
            'items' => [
                [
                    'section' => 'API / Create User',
                    'title' => 'Creates a User',
                    'url' => '/docs/api-create-user/creates-a-user.html',
                ],
                [
                    'section' => 'Feature / List Users',
                    'title' => 'Lists Users',
                    'url' => '/docs/feature-list-users/lists-users.html',
                ],
                [
                    'section' => 'Feature / List Users',
                    'title' => 'Paginates Users',
                    'url' => '/docs/feature-list-users/paginates-users.html',
                ],
            ]
        ];
        $this->assertSame($expectedJson, json_decode(file_get_contents(__DIR__.'/public/docs/search.json'), JSON_OBJECT_AS_ARRAY));
    }

    /** @test */
    function replaces_the_original_urls_with_relative_urls()
    {
        $run = $this->createRun('main', 'abcde', true);

        $baseRunUrl = url("enlighten/run/{$run->id}");

        $this->expectContentRequest($run->url)->andReturn('
            <link rel="stylesheet" href="/vendor/enlighten/css/app.css?0.2.0">
            <h1>Enlighten</h1>
            <a href="'.$baseRunUrl.'"></a>
            <a href="'.$baseRunUrl.'/features"></a>
            <p>https://github.com/Stydenet/enlighten</p>
            <div data-search="fetch(\'/search.json\')"></div>
        ');

        $this->exporter->export($run, __DIR__.'/public/docs', '/docs');

        $this->assertDocumentHasContent('
            <link rel="stylesheet" href="/docs/assets/css/app.css?0.2.0">
            <h1>Enlighten</h1>
            <a href="/docs"></a>
            <a href="/docs/features.html"></a>
            <p>https://github.com/Stydenet/enlighten</p>
            <div data-search="fetch(\'/docs/search.json\')"></div>
        ', 'index.html');
    }

    /** @test */
    function replaces_the_original_urls_with_absolute_urls()
    {
        $run = $this->createRun('main', 'abcde', true);

        $baseRunUrl = url("enlighten/run/{$run->id}");

        $this->expectContentRequest($run->url)->andReturn('
            <link rel="stylesheet" href="/vendor/enlighten/css/app.css?0.2.0">
            <h1>Enlighten</h1>
            <a href="'.$baseRunUrl.'"></a>
            <a href="'.$baseRunUrl.'/features"></a>
            <p>https://github.com/Stydenet/enlighten</p>
            <div data-search="fetch(\'/search.json\')"></div>
        ');

        $this->exporter->export($run, __DIR__.'/public/docs', 'https://example.com');

        $this->assertDocumentHasContent('
            <link rel="stylesheet" href="https://example.com/assets/css/app.css?0.2.0">
            <h1>Enlighten</h1>
            <a href="https://example.com"></a>
            <a href="https://example.com/features.html"></a>
            <p>https://github.com/Stydenet/enlighten</p>
            <div data-search="fetch(\'https://example.com/search.json\')"></div>
        ', 'index.html');
    }

    /** @test */
    function replaces_the_search_file_path_with_the_export_base_path()
    {
        $run = $this->createRun('main', 'abcde', true);

        $baseRunUrl = url("enlighten/run/{$run->id}");

        $this->expectContentRequest($run->url)->andReturn('
            <div data-search="fetch(\'/docs/search.json\')"></div>
        ');

        $this->exporter->export($run, __DIR__.'/public/docs', '/v-1');

        $this->assertDocumentHasContent('
            <div data-search="fetch(\'/v-1/search.json\')"></div>
        ', 'index.html');
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
