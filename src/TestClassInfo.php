<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;

class TestClassInfo
{
    private string $className;

    private array $config;

    private array $texts;

    public function __construct(string $className, array $config = [], array $texts = [])
    {
        $this->className = $className;
        $this->config = $config;

        $this->texts = $texts;
    }

    public function getClassName()
    {
        return $this->className;
    }

    public function getConfig()
    {
        return $this->config;
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
}
