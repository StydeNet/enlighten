<?php

namespace Styde\Enlighten\Utils;

use Closure;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class Annotations
{
    protected static $casts = [];

    public function addCast(string $key, Closure $callback)
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
        return Collection::make(explode(PHP_EOL, $docComment))
            ->slice(1, -1)
            ->map(function ($line) {
                return ltrim(rtrim($line, ' .'), '* ');
            })
            ->pipe(function ($collection) {
                return Collection::make(static::chunkByAnnotation($collection));
            })
            ->map(function ($value, $name) {
                return static::applyCast($name, trim($value));
            });
    }

    private static function chunkByAnnotation(Collection $lines)
    {
        $result = [];

        foreach ($lines as $line) {
            if (preg_match("#^@(\w+)(.*?)?$#", $line, $matches)) {
                $currentAnnotation = $matches[1];
                $result[$currentAnnotation] = $matches[2] ?? '';
                continue;
            }

            if (isset($currentAnnotation)) {
                $result[$currentAnnotation] .= PHP_EOL.$line;
            }
        }

        return $result;
    }

    private static function applyCast($name, $value)
    {
        if (empty(static::$casts[$name])) {
            return $value;
        }

        return call_user_func(static::$casts[$name], $value);
    }
}
