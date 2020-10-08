<?php

namespace Styde\Enlighten\Utils;

use Closure;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class Annotations
{
    protected static $casts = [];

    public static function addCast(string $key, Closure $callback)
    {
        static::$casts[$key] = $callback;
    }

    public function getFromClass($class): Collection
    {
        $reflectionClass = new ReflectionClass($class);

        return static::fromDocComment($reflectionClass->getDocComment());
    }

    public function getFromMethod($class, $method): Collection
    {
        $reflectionMethod = new ReflectionMethod($class, $method);

        return static::fromDocComment($reflectionMethod->getDocComment());
    }

    protected static function fromDocComment($docComment)
    {
        preg_match_all("#@(\w+)( (.*?))?\n#s", $docComment, $matches);

        return Collection::make($matches[1])
            ->combine($matches[3])
            ->map(function ($value) {
                return trim($value, '. ');
            })
            ->map(function ($value, $name) {
                return static::applyCast($name, $value);
            });
    }

    private static function applyCast($name, $value)
    {
        if (empty (static::$casts[$name])) {
            return $value;
        }

        return call_user_func(static::$casts[$name], $value);
    }
}
