<?php

namespace Styde\Enlighten;

class IgnoredTest extends TestInfo
{
    public function isIgnored(): bool
    {
        return true;
    }

    public function __call($name, $arguments)
    {
        // Does nothing.
    }
}
