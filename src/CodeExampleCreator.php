<?php

namespace Styde\Enlighten;

use Closure;
use Throwable;

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

        $result = null;

        try {
            $result = $callback(...$params);

            $testExample->saveSnippetResult($result);
        } catch (Throwable $throwable) {
            $testExample->setException($throwable);
        }

        return $result;
    }
}
