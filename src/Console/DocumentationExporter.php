<?php

namespace Styde\Enlighten\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Run;

class DocumentationExporter
{
    /**
     * @var string
     */
    private $baseDir;

    /**
     * @var Filesystem
     */
    private $file;

    public function __construct(Filesystem $file, $baseDir = 'public/docs')
    {
        $this->baseDir = $baseDir;
        $this->file = $file;
    }

    public function export(Run $run)
    {
        $this->createDirectory('/');
        $this->createFile('index.html', $this->getContentFrom($run->url));

        $run->groups->each(function ($group) {
            $this->exportGroupWithExamples($group);
        });
    }

    private function exportGroupWithExamples($group)
    {
        $this->createFile("{$group->slug}.html", 'Group');

        $this->createDirectory($group->slug);

        $group->examples->each(function (Example $example) use ($group) {
            $this->exportExample($example->setRelation('group', $group));
        });
    }

    private function exportExample($example)
    {
        $this->createFile(
            "{$example->group->slug}/{$example->method_name}.html",
            'Example'
        );
    }

    private function createDirectory($path)
    {
        if ($this->file->isDirectory("{$this->baseDir}/$path")) {
            return;
        }

        $this->file->makeDirectory("{$this->baseDir}/$path", 0755, true);
    }

    private function createFile(string $filename, string $contents)
    {
        $this->file->put("{$this->baseDir}/{$filename}", $contents);
    }

    private function getContentFrom(string $url): string
    {
        return Http::get($url)->body();
    }
}
