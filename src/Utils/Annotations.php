<?php

namespace Styde\Enlighten\Utils;

use Closure;
use Illuminate\Support\Collection;
use ReflectionClass;
use ReflectionMethod;

class Annotations
{
    protected $casts = [];

    public function addCast(string $key, Closure $callback): void
    {
        $this->casts[$key] = $callback;
    }

    public function getFromClass($class): Collection
    {
        $reflectionClass = new ReflectionClass($class);

        return $this->fromDocComment($reflectionClass->getDocComment());
    }

    public function getFromMethod($class, $method): Collection
    {
        $reflectionMethod = new ReflectionMethod($class, $method);

        return $this->fromDocComment($reflectionMethod->getDocComment());
    }

    protected function fromDocComment($docComment)
    {
        return Collection::make(explode(PHP_EOL, trim((string) $docComment, '/*')))
            ->map(fn($line) => ltrim(rtrim((string) $line, ' .'), '* '))
            ->pipe(fn($collection) => Collection::make(static::chunkByAnnotation($collection)))
            ->map(fn($value, $name) => static::applyCast($name, trim((string) $value)));
    }

    protected function chunkByAnnotation(Collection $lines)
    {
        $result = [];

        foreach ($lines as $line) {
            if (preg_match("#^@(\w+)(.*?)?$#", (string) $line, $matches)) {
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

    protected function applyCast($name, $value)
    {
        if (empty($this->casts[$name])) {
            return $value;
        }

        return call_user_func($this->casts[$name], $value);
    }
}
