<?php

namespace Styde\Enlighten;

class GitInfo
{
    public static function currentBranch()
    {
        return exec('git branch --show-current');
    }

    public static function head()
    {
        return exec('git rev-parse --short HEAD');
    }

    public static function modified()
    {
        return exec('git status --porcelain') !== '';
    }
}
