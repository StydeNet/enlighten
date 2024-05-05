<?php

namespace Styde\Enlighten\CodeExamples;

use Styde\Enlighten\ExampleCreator;
use Throwable;

readonly class CodeExampleCreator
{
    public function __construct(
        private ExampleCreator $exampleCreator,
        private CodeInspector $codeInspector,
    ) {
    }

    public function createSnippet(callable $callback, string $key = null)
    {
        $testExample = $this->exampleCreator->getCurrentExample();

        if (is_null($testExample)) {
            return $callback();
        }

        $testExample->addSnippet($key, $this->codeInspector->getCodeFrom($callback));

        try {
            $result = call_user_func($callback);

            $testExample->setSnippetResult(CodeResultTransformer::export($result));

            return $result;
        } catch (Throwable $throwable) {
            $this->exampleCreator->captureException($throwable);

            throw $throwable;
        }
    }
}
