<?php

namespace Styde\Enlighten;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;

class IgnoredTest implements TestInfo
{
    public function isIgnored(): bool
    {
        return true;
    }

    public function save(): Model
    {
        throw new BadMethodCallException('Excluded tests should not be persisted');
    }
}
