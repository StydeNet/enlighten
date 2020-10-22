<?php

namespace Styde\Enlighten;

use Closure;
use ReflectionFunction;

class CodeExampleCreator
{
    /**
     * @var TestInspector
     */
    private $testInspector;

    /**
     * @var CodeInspector
     */
    private $codeInspector;

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
