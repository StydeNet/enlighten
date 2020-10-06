<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Styde\Enlighten\Models\ExampleGroup;

class TestClassInfo
{
    private TestRun $testRun;
    private string $className;
    private array $options;
    private array $texts;
    protected ?ExampleGroup $exampleGroup = null;

    public function __construct(TestRun $testRun, string $className, array $texts = [], array $options = [])
    {
        $this->testRun = $testRun;
        $this->className = $className;
        $this->options = $options;
        $this->texts = $texts;
    }

    public function is(string $name): bool
    {
        return $this->className === $name;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function save(): Model
    {
        if ($this->exampleGroup == null) {
            $run = $this->testRun->save();

            $this->exampleGroup = ExampleGroup::firstOrNew([
                'run_id' => $run->id,
                'class_name' => $this->getClassName(),
            ]);
        }

        $this->exampleGroup->fill([
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ])
        ->save();

        return $this->exampleGroup;
    }

    public function getTitle()
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    private function getDescription(): ?string
    {
        return $this->texts['description'] ?? null;
    }

    public function getDefaultTitle(): string
    {
        $result = Str::of(class_basename($this->className));

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return $result->replaceMatches('@([A-Z])@', ' $1')->trim();
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
