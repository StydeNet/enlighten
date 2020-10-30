<?php

namespace Styde\Enlighten\Console;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\File;
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
        if (! $this->file->isDirectory($this->baseDir)) {
            $this->file->makeDirectory($this->baseDir, 0755, true);
        }

        $this->file->put("{$this->baseDir}/index.html", 'Index');

        $run->groups->each(function ($group) {
            $this->exportGroupWithExamples($group);
        });
    }

    private function exportGroupWithExamples($group)
    {
        $this->file->put("{$this->baseDir}/{$group->slug}.html", 'Group');

        if (! $this->file->isDirectory("{$this->baseDir}/{$group->slug}")) {
            $this->file->makeDirectory($this->baseDir, 0755, true);
        }

        $group->examples->each(function ($example) use ($group) {
            $example->setRelation('group', $group);
            $this->exportExample($example);
        });
    }

    private function exportExample($example)
    {
        $this->file->put("{$this->baseDir}/{$example->group->slug}/{$example->method_name}.html", 'Example');
    }

    private function createDirectory($path)
    {
        if ($this->file->isDirectory($path)) {
            return;
        }

        $this->file->makeDirectory($path, 0755, true);
    }
}
