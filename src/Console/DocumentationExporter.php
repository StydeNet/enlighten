<?php

namespace Styde\Enlighten\Console;

use Illuminate\Filesystem\Filesystem;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class DocumentationExporter
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var string
     */
    private $baseDir;
    /**
     * @var ContentRequest
     */
    private $request;

    /**
     * @var string
     */
    protected $originalBaseUrl;

    /**
     * @var string
     */
    protected $staticBaseUrl;

    public function __construct(Filesystem $filesystem, ContentRequest $request)
    {
        $this->filesystem = $filesystem;
        $this->request = $request;
    }

    public function export(Run $run, string $baseDir, string $staticBaseUrl)
    {
        $this->baseDir = rtrim($baseDir, '/');
        $this->staticBaseUrl = rtrim($staticBaseUrl, '/');
        $this->originalBaseUrl = $run->base_url;

        $this->createDirectory('/');

        $this->exportAssets();

        $this->exportRunWithAreas($run);

        $run->groups->each(function ($group) {
            $this->exportGroupWithExamples($group);
        });

        $this->exportSearchJson($run);
    }

    private function exportAssets()
    {
        $this->filesystem->deleteDirectory("{$this->baseDir}/assets");

        $this->filesystem->copyDirectory(__DIR__.'/../../dist', "{$this->baseDir}/assets");
    }

    private function exportRunWithAreas(Run $run)
    {
        $this->createHtmlFile('index', $this->withContentFrom($run->url));

        $this->createDirectory('/areas');

        $run->areas->each(function ($area) use ($run) {
            $this->exportArea($run, $area);
        });
    }

    private function exportArea(Run $run, Area $area)
    {
        $this->createHtmlFile("areas/{$area->slug}", $this->withContentFrom($run->areaUrl($area->slug)));
    }

    private function exportGroupWithExamples(ExampleGroup $group)
    {
        $this->createHtmlFile("{$group->slug}", $this->withContentFrom($group->url));

        $this->createDirectory($group->slug);

        $group->examples->each(function (Example $example) use ($group) {
            $this->exportExample($example->setRelation('group', $group));
        });
    }

    private function exportExample(Example $example)
    {
        $this->createHtmlFile(
            "{$example->group->slug}/{$example->slug}",
            $this->withContentFrom($example->url)
        );
    }

    private function exportSearchJson(Run $run)
    {
        $this->filesystem->put(
            "{$this->baseDir}/search.json",
            json_encode(['items' => $this->getSearchItems($run)], JSON_THROW_ON_ERROR)
        );
    }

    private function getSearchItems(Run $run)
    {
        return $run->groups
            ->load('examples')
            ->flatMap(function ($group) {
                return $group->examples->map(function ($example) use ($group) {
                    return [
                        'section' => "{$group->area_title} / {$group->title}",
                        'title' => $example->title,
                        'url' => $this->getStaticUrl($example->url),
                    ];
                });
            })
            ->sortBy('title')
            ->values();
    }

    private function createDirectory($path)
    {
        if ($this->filesystem->isDirectory("{$this->baseDir}/$path")) {
            return;
        }

        $this->filesystem->makeDirectory("{$this->baseDir}/$path", 0755);
    }

    private function createHtmlFile(string $filename, string $contents)
    {
        $this->filesystem->put("{$this->baseDir}/{$filename}.html", $contents);
    }

    private function withContentFrom(string $url): string
    {
        return $this->replaceUrls($this->request->getContent($url));
    }

    private function replaceUrls(string $contents)
    {
        return preg_replace_callback(
            '@'.$this->originalBaseUrl.'([^"]+)?@',
            function ($matches) {
                return $this->getStaticUrl($matches[0]);
            },
            str_replace('/vendor/enlighten/', "{$this->staticBaseUrl}/assets/", $contents)
        );
    }

    private function getStaticUrl(string $originalUrl): string
    {
        $result = str_replace($this->originalBaseUrl, $this->staticBaseUrl, $originalUrl);

        if ($result === $this->staticBaseUrl) {
            return $result;
        }

        return "{$result}.html";
    }
}
