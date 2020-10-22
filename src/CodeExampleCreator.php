<?php

namespace Styde\Enlighten;

use Closure;
use ReflectionFunction;

class CodeExampleCreator
{
    private TestInspector $testInspector;
    private CodeInspector $codeInspector;

    public function __construct(TestInspector $testInspector, CodeInspector $codeInspector)
    {
        $this->testInspector = $testInspector;
        $this->codeInspector = $codeInspector;
    }

    public function createSnippet(Closure $callback)
    {
        $testExample = $this->testInspector->getCurrentTestExample();

        if ($testExample->isIgnored()) {
            return $callback;
        }

        $reflection = new ReflectionFunction($callback);

        return new CodeSnippet(
            $testExample,
            $callback,
            $this->codeInspector->getCode($reflection),
            $reflection->getParameters()
        );
    }
}
