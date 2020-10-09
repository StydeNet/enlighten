<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;
use Styde\Enlighten\Models\ExampleGroup;

class TestClassInfo
{
    private TestRun $testRun;
    private string $className;
    private array $texts;
    protected ?ExampleGroup $exampleGroup = null;

    public function __construct(TestRun $testRun, string $className, array $texts = [])
    {
        $this->testRun = $testRun;
        $this->className = $className;
        $this->texts = $texts;
    }

    public function is(string $name): bool
    {
        return $this->className === $name;
    }

    public function getClassName(): string
    {
        return $this->className;
    }

    public function save(): ExampleGroup
    {
        if ($this->exampleGroup != null) {
            return $this->exampleGroup;
        }

        $run = $this->testRun->save();

        return $this->exampleGroup = ExampleGroup::updateOrCreate([
            'run_id' => $run->id,
            'class_name' => $this->getClassName(),
        ], [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ]);
    }

    public function getTitle(): string
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
}
