<?php

namespace Styde\Enlighten;

class IgnoredTest implements TestInfo
{
    private string $className;
    private string $methodName;

    public function __construct(string $className, string $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
    }

    public function isIgnored(): bool
    {
        return true;
    }

    public function is(string $className, string $methodName): bool
    {
        return $this->className == $className && $this->methodName == $methodName;
    }
}
