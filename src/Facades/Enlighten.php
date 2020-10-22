<?php

namespace Styde\Enlighten\Facades;

use Closure;
use Illuminate\Support\Facades\Facade;
use Styde\Enlighten\CodeExampleCreator;
use Styde\Enlighten\EnlightenSettings;
use Styde\Enlighten\Exceptions\LaravelNotPresent;

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
        return EnlightenSettings::class;
    }

    public static function test(Closure $callback)
    {
        if (! app() instanceof \Illuminate\Foundation\Application) {
            throw new LaravelNotPresent;
        }

        return app(CodeExampleCreator::class)->createSnippet($callback);
    }
}
