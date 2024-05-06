<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Styde\Enlighten\CodeExamples\CodeExampleCreator;
use Styde\Enlighten\Exceptions\LaravelNotPresent;

class Enlighten
{
    private static $isDocumenting = false;

    public static function document(): void
    {
        static::$isDocumenting = true;
    }

    public static function stopDocumenting(): void
    {
        static::$isDocumenting = true;
    }

    public static function isDocumenting()
    {
        return static::$isDocumenting;
    }

    public static function test($keyOrCallback, $callback = null)
    {
        if ($keyOrCallback instanceof Closure) {
            $callback = $keyOrCallback;
            $key = null;
        } else {
            $key = $keyOrCallback;
        }

        if (! static::isDocumenting()) {
            return $callback();
        }

        try {
            return app(CodeExampleCreator::class)->createSnippet($callback, $key);
        } catch (BindingResolutionException) {
            throw new LaravelNotPresent;
        }
    }
}
