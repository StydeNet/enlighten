<?php

namespace Styde\Enlighten\Utils;

use Styde\Enlighten\Contracts\VersionControl;

class Git implements VersionControl
{
    public function currentBranch(): string
    {
        $refs = exec('git symbolic-ref HEAD');
        $refs = str_replace("refs/heads/", "", $refs);

        return $refs;
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
