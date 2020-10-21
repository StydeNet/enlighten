<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Enumerable;
use Styde\Enlighten\Models\ExampleSnippet;
use Throwable;

class CodeExampleCreator
{
    public static int $maxNestedLevel = 5;

    private TestInspector $testInspector;
    private CodeInspector $codeInspector;

    public function __construct(TestInspector $testInspector, CodeInspector $codeInspector)
    {
        $this->testInspector = $testInspector;
        $this->codeInspector = $codeInspector;
    }

    public function createSnippet(Closure $callback, $params)
    {
        $testExample = $this->testInspector->getCurrentTestExample();

        if ($testExample->isIgnored()) {
            return $callback(...$params);
        }

        $codeSnippet = $this->codeInspector->getInfoFrom($callback, $this->exportResult($params));

        $testExample->createSnippet($codeSnippet);

        $result = null;

        try {
            $result = $callback(...$params);

            $testExample->saveSnippetResult($this->exportResult($result));
        } catch (Throwable $throwable) {
            $testExample->setException($throwable);

            throw $throwable;
        }

        return $result;
    }

    private function exportResult($result, int $currentLevel = 0)
    {
        if (is_object($result)) {
            return $this->exportObject($result, $currentLevel);
        }

        if (! is_array($result)) {
            return $result;
        }

        return array_map(fn($item) => $this->exportResult($item, $currentLevel), $result);
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

        return $this->exportResult($this->getObjectAttributes($result), $currentLevel + 1);
    }

    private function getObjectAttributes(object $object)
    {
        if ($object instanceof Enumerable) {
            return $object->all();
        }

        if ($object instanceof Arrayable) {
            return $object->toArray();
        }

        return get_object_vars($object);
    }
}
