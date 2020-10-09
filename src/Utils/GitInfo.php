<?php

namespace Styde\Enlighten\Utils;

class GitInfo
{
    public function currentBranch()
    {
        return exec('git branch --show-current');
    }

    public function head()
    {
        return exec('git rev-parse --short HEAD');
    }

    public function modified()
    {
        return exec('git status --porcelain') !== '';
    }
}
