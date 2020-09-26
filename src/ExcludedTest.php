<?php

namespace Styde\Enlighten;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Model;

class ExcludedTest implements TestInfo
{
    public function isExcluded(): bool
    {
        return true;
    }

    public function save(): Model
    {
        throw new BadMethodCallException('Excluded tests should not be persisted');
    }
}
