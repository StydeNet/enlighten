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

    public function addTestRun(TestRun $testRun)
    {
        $this->testRun = $testRun;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getTitle()
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    public function getDescription(): ?string
    {
        return $this->texts['description'] ?? null;
    }

    protected function getDefaultTitle(): string
    {
        $result = Str::of(class_basename($this->className));

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return $result->replaceMatches('@([A-Z])@', ' $1')->trim();
    }

    public function isIgnored(): bool
    {
        return false;
    }

    public function save(): Model
    {
        $run = $this->testRun->save();

        if ($this->exampleGroup == null) {
            $this->exampleGroup = ExampleGroup::firstOrNew([
                'run_id' => $run->id,
                'class_name' => $this->getClassName(),
            ]);
        }

        $this->exampleGroup->fill([
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ]);

        $this->exampleGroup->save();

        return $this->exampleGroup;
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
