<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;
use Styde\Enlighten\Models\ExampleGroup;

class TestExampleGroup
{
    private TestRun $testRun;
    private string $className;
    private array $texts;
    protected ?ExampleGroup $exampleGroup = null;

    public function __construct(string $className, array $texts = [])
    {
        $this->testRun = TestRun::getInstance();
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

        return $this->exampleGroup = ExampleGroup::create([
            'run_id' => $run->id,
            'class_name' => $this->getClassName(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'area' => $this->getArea(),
            'slug' => $this->getSlug()
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

    private function getArea(): string
    {
        return Str::slug(explode('\\', $this->getClassName())[1]);
    }

    private function getSlug(): string
    {
        return Str::slug($this->getDefaultTitle());
    }

    public function getDefaultTitle(): string
    {
        $result = Str::of(class_basename($this->getClassName()));

        if ($result->endsWith('Test')) {
            $result = $result->substr(0, -4);
        }

        return $result->replaceMatches('@([A-Z])@', ' $1')->trim();
    }
}
