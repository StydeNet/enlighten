<?php

namespace Styde\Enlighten;

interface TestInfo
{
    public function is(string $className, string $methodName): bool;

    public function isIgnored(): bool;
}
