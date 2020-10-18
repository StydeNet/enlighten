<?php

namespace Styde\Enlighten;

use Closure;

class CodeExampleCreator
{
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

        $codeSnippet = $this->codeInspector->getInfoFrom($callback, $params);

        $testExample->createSnippet($codeSnippet);

        $result = $callback(...$params);

        $testExample->saveSnippetResult($result);

        return $result;
    }
}
