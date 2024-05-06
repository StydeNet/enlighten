<?php

namespace Styde\Enlighten\CodeExamples;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Enumerable;
use ReflectionFunction;
use ReflectionParameter;
use Styde\Enlighten\Models\ExampleSnippet;

class CodeResultTransformer
{
    public static int $maxNestedLevel = 5;

    public static function export($result)
    {
        return (new static)->transformInArray($result);
    }

    public static function exportProvidedData(array $data): array
    {
        return static::export($data);
    }

    private function transformInArray($result, int $currentLevel = 0)
    {
        if ($result instanceof Closure) {
            return $this->exportFunction($result);
        }

        if (is_object($result)) {
            return $this->exportObject($result, $currentLevel);
        }

        if (! is_array($result)) {
            return $result;
        }

        return array_map(fn($item) => $this->transformInArray($item, $currentLevel), $result);
    }

    private function exportFunction($result): array
    {
        $functionReflection = new ReflectionFunction($result);

        return [
            ExampleSnippet::FUNCTION => ExampleSnippet::ANONYMOUS_FUNCTION,
            ExampleSnippet::PARAMETERS => $this->exportParameters($functionReflection->getParameters()),
            ExampleSnippet::RETURN_TYPE => $functionReflection->hasReturnType() ? $functionReflection->getReturnType()->getName(): null,
        ];
    }

    private function exportParameters(array $parameters): array
    {
        return collect($parameters)
            ->map(fn(ReflectionParameter $parameter) => [
                ExampleSnippet::TYPE => $parameter->hasType() ? $parameter->getType()->getName() : null,
                ExampleSnippet::PARAMETER => $parameter->getName(),
                ExampleSnippet::OPTIONAL => $parameter->isOptional(),
                ExampleSnippet::DEFAULT => $parameter->isOptional() ? $parameter->getDefaultValue() : null,
            ])
            ->all();
    }

    private function exportObject(object $result, int $currentLevel): array
    {
        return [
            ExampleSnippet::CLASS_NAME => $result::class,
            ExampleSnippet::ATTRIBUTES => $this->exportAttributes($result, $currentLevel),
        ];
    }

    private function exportAttributes(object $result, int $currentLevel)
    {
        if ($currentLevel >= static::$maxNestedLevel) {
            return null;
        }

        return $this->transformInArray($this->getObjectAttributes($result), $currentLevel + 1);
    }

    private function getObjectAttributes(object $object)
    {
        if ($object instanceof Enumerable) {
            return ['items' => $object->all()];
        }

        if ($object instanceof Arrayable) {
            return $object->toArray();
        }

        return get_object_vars($object);
    }
}
