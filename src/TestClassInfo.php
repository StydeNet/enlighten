<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Styde\Enlighten\Models\ExampleGroup;

class TestClassInfo implements TestInfo
{
    private string $className;

    private array $options;

    private array $texts;

    public function __construct(string $className, array $texts = [], array $options = [])
    {
        $this->className = $className;
        $this->options = $options;
        $this->texts = $texts;
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
        return ExampleGroup::updateOrCreate([
            'class_name' => $this->getClassName(),
        ], [
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
        ]);
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
