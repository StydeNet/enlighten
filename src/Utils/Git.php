<?php

namespace Styde\Enlighten\Utils;

use Styde\Enlighten\Contracts\VersionControl;

class Git implements VersionControl
{
    public function currentBranch(): string
    {
        return exec('git branch --show-current');
    }

    public function head(): string
    {
        return exec('git rev-parse --short HEAD');
    }

    public function modified(): bool
    {
        return exec('git status --porcelain') !== '';
    }
}
