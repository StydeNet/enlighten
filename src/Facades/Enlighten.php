<?php

namespace Styde\Enlighten\Facades;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Facade;
use Styde\Enlighten\CodeSnippets\CodeExampleCreator;
use Styde\Enlighten\EnlightenSettings;
use Styde\Enlighten\Exceptions\LaravelNotPresent;

/**
 * @method static self setCustomAreaResolver(Closure $callback)
 * @method static string getAreaSlug(string $className)
 * @method static self setCustomTitleGenerator(Closure $callback)
 * @method static string generateTitleFromMethodName(string $methodName)
 * @method static string generateTitleFromClassName(string $className)
 * @method static self setCustomSlugGenerator(Closure $callback)
 * @method static string generateSlugFromClassName($className)
 * @method static string generateSlugFromMethodName($methodName)
 * @method static bool hide(string $sectionName)
 * @method static bool show(string $sectionName)
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
        try {
            return app(CodeExampleCreator::class)->createSnippet($callback);
        } catch (BindingResolutionException $exception) {
            throw new LaravelNotPresent;
        }
    }
}
