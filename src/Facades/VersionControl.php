<?php

namespace Styde\Enlighten\Facades;

use Illuminate\Support\Facades\Facade;
use Styde\Enlighten\Contracts\VersionControl as VersionControlContract;

/**
 * @method static string currentBranch
 * @method static string head
 * @method static string modified
 *
 * @see \Styde\Enlighten\Contracts\VersionControl
 * @see \Styde\Enlighten\Utils\Git
 */
class VersionControl extends Facade
{
    public static function getFacadeAccessor()
    {
        return VersionControlContract::class;
    }
}
