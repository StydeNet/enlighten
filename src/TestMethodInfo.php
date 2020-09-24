<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TestMethodInfo
{
    public TestClassInfo $classInfo;
    private string $methodName;
    private array $config;
    private array $texts;

    public function __construct(TestClassInfo $classInfo, string $methodName, array $config, array $texts = [])
    {
        $this->classInfo = $classInfo;
        $this->methodName = $methodName;
        $this->config = $config;
        $this->texts = $texts;
    }

    public function getMethodName()
    {
        return $this->methodName;
    }

    public function getTitle(): string
    {
        return $this->texts['title'] ?? $this->getDefaultTitle();
    }

    protected function getDefaultTitle(): string
    {
        return ucfirst(str_replace('_', ' ', $this->getMethodName()));
    }

    public function getDescription(): ?string
    {
        return $this->texts['description'] ?? null;
    }

    public function isExcluded($patterns)
    {
        if (Str::is($patterns, $this->classInfo->getClassName()) || Str::is($patterns, $this->methodName)) {
            return true;
        }

        return Arr::get($this->config, 'exclude', false);
    }
}
