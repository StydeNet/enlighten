<?php

namespace Styde\Enlighten\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Styde\Enlighten\CodeExampleCreator;

/**
 * @method static self setCustomAreaResolver(Closure $callback)
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

    public static function test(Closure $callback)
    {
        return app(CodeExampleCreator::class)->createSnippet($callback);
    }
}
