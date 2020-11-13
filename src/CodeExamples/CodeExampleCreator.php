<?php

namespace Styde\Enlighten\CodeExamples;

use Closure;
use Styde\Enlighten\ExampleBuilder;
use Styde\Enlighten\ExampleCreator;
use Throwable;

class CodeExampleCreator
{
    /**
     * @var \Styde\Enlighten\ExampleCreator
     */
    private $exampleCreator;

    /**
     * @var CodeInspector
     */
    private $codeInspector;

    public function __construct(ExampleCreator $exampleCreator, CodeInspector $codeInspector)
    {
        $this->exampleCreator = $exampleCreator;
        $this->codeInspector = $codeInspector;
    }

    public function createSnippet($key, $callback = null)
    {
        if ($key instanceof Closure) {
            $callback = $key;
            $key = null;
        }

        $testExample = $this->exampleCreator->getCurrentExample();

        if (is_null($testExample)) {
            return $callback();
        }

        /** @var ExampleBuilder $testExample */
        $testExample->createSnippet($key, $this->codeInspector->getCodeFrom($callback));

        try {
            $result = call_user_func($callback);

            $testExample->saveSnippetResult(CodeResultTransformer::toArray($result));

            return $result;
        } catch (Throwable $throwable) {
            $this->exampleCreator->captureException($throwable);

            throw $throwable;
        }
    }
}
