<?php

namespace Styde\Enlighten\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string currentBranch
 * @method static string head
 * @method static string modified
 */
class GitInfo extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Styde\Enlighten\Utils\GitInfo::class;
    }
}
