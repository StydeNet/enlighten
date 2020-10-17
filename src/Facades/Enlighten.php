<?php

namespace Styde\Enlighten\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self setCustomAreaResolver(\Closure $callback)
 * @method static string getAreaSlug(string $className)
 *
 * @see \Styde\Enlighten\EnlightenSettings
 */
class Enlighten extends Facade
{
    public static function getFacadeAccessor()
    {
        return \Styde\Enlighten\EnlightenSettings::class;
    }
}
