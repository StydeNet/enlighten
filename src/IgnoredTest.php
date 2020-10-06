<?php

namespace Styde\Enlighten;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;

class IgnoredTest implements TestInfo
{
    private string $className;
    private string $methodName;

    public function __construct(string $className, string $methodName)
    {
        $this->className = $className;
        $this->methodName = $methodName;
    }

    public function addLine(): self
    {
        return $this;
    }

    public function isIgnored(): bool
    {
        return true;
    }

    public function save(): Model
    {
        throw new BadMethodCallException('Excluded tests should not be persisted');
    }

    public function is(string $className, string $methodName): bool
    {
        return $this->className == $className && $this->methodName == $methodName;
    }
}
