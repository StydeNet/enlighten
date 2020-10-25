<?php

namespace Styde\Enlighten\Utils;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Enumerable;
use Styde\Enlighten\Models\ExampleSnippet;

class ResultTransformer
{
    /**
     * @var int
     */
    public static $maxNestedLevel = 5;

    public static function toArray($result)
    {
        return (new static)->transformInArray($result);
    }

    private function transformInArray($result, int $currentLevel = 0)
    {
        if (is_object($result)) {
            return $this->exportObject($result, $currentLevel);
        }

        if (! is_array($result)) {
            return $result;
        }

        return array_map(function ($item) use ($currentLevel) {
            return $this->transformInArray($item, $currentLevel);
        }, $result);
    }

    private function exportObject(object $result, int $currentLevel)
    {
        return [
            ExampleSnippet::CLASS_NAME => get_class($result),
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
