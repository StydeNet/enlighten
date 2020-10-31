<?php

namespace Styde\Enlighten\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Routing\UrlGenerator;
use Styde\Enlighten\Models\Example;
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
    protected $currentBaseUrl;

    /**
     * @var string
     */
    protected $runBaseUrl;

    /**
     * @var string
     */
    protected $staticBaseUrl;

    public function __construct(Filesystem $filesystem, ContentRequest $request, string $currentBaseUrl)
    {
        $this->filesystem = $filesystem;
        $this->request = $request;
        $this->currentBaseUrl = $currentBaseUrl;
    }

    public function export(Run $run, string $baseDir, string $staticBaseUrl)
    {
        $this->baseDir = $baseDir;
        $this->runBaseUrl = "{$this->currentBaseUrl}enlighten/run/{$run->id}";
        $this->staticBaseUrl = $staticBaseUrl;

        $this->createDirectory('/');

        $this->createFile('index.html', $this->withContentFrom($run->url));

        $run->groups->each(function ($group) {
            $this->exportGroupWithExamples($group);
        });
    }

    private function exportGroupWithExamples($group)
    {
        $this->createFile("{$group->slug}.html", $this->withContentFrom($group->url));

        $this->createDirectory($group->slug);

        $group->examples->each(function (Example $example) use ($group) {
            $this->exportExample($example->setRelation('group', $group));
        });
    }

    private function exportExample($example)
    {
        $this->createFile(
            "{$example->group->slug}/{$example->method_name}.html",
            $this->withContentFrom($example->url)
        );
    }

    private function createDirectory($path)
    {
        if ($this->filesystem->isDirectory("{$this->baseDir}/$path")) {
            return;
        }

        $this->filesystem->makeDirectory("{$this->baseDir}/$path", 0755, true);
    }

    private function createFile(string $filename, string $contents)
    {
        $this->filesystem->put("{$this->baseDir}/{$filename}", $contents);
    }

    private function withContentFrom(string $url): string
    {
        return $this->replaceUrls($this->request->getContent($url));
    }

    private function replaceUrls(string $contents)
    {
        return preg_replace_callback(
            "@{$this->runBaseUrl}([^\"]+)?@",
            function ($matches) {
                return $this->getStaticUrl($matches[0]);
            },
            $contents
        );
    }

    private function getStaticUrl(string $originalUrl): string
    {
        $result = str_replace($this->runBaseUrl, $this->staticBaseUrl, $originalUrl);

        if ($result === $this->staticBaseUrl) {
            return $result;
        }

        return $result.'.html';
    }
}
