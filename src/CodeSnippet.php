<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Enumerable;
use Styde\Enlighten\Models\ExampleSnippet;
use Throwable;

class CodeSnippet
{
    public static int $maxNestedLevel = 5;

    private ?ExampleSnippet $snippet = null;
    private Closure $callback;
    private string $code;
    private array $params;

    public function __construct(TestExample $testExample, Closure $callback, string $code, array $params)
    {
        $this->testExample = $testExample;
        $this->code = $code;
        $this->params = $params;
        $this->callback = $callback;
    }

    public function __invoke(...$args)
    {
        if (is_null($this->snippet)) {
            $this->snippet = $this->testExample->createSnippet($this->code);
        }

        $call = $this->testExample->createSnippetCall($this->snippet, $this->exportResult($this->getArguments($args)));

        try {
            $result = call_user_func($this->callback, ...$args);

            $this->testExample->saveSnippetCallResult($this->exportResult($result));

            return $result;
        } catch (Throwable $throwable) {
            $this->testExample->setException($throwable);

            throw $throwable;
        }
    }

    /**
     * Get the arguments as an associative array (parameter name => argument value or default value).
     */
    private function getArguments($args): array
    {
        return collect($this->params)
            ->mapWithKeys(function ($parameter, $index) use ($args) {
                return [$parameter->getName() => $args[$index] ?? $parameter->getDefaultValue()];
            })
            ->all();
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
