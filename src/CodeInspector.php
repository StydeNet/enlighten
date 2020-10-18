<?php

namespace Styde\Enlighten;

use Closure;
use ReflectionFunction;

class CodeInspector
{
    public function getInfoFrom(Closure $snippet, array $args = [])
    {
        $reflection = new ReflectionFunction($snippet);

        return new CodeSnippet(
            $this->getCodeFrom($reflection),
            $this->getParameters($reflection, $args),
        );
    }

    public function getCodeFrom(ReflectionFunction $reflection): string
    {
        // Get the body of the function.
        $snippet = trim(implode(
            PHP_EOL,
            array_slice(
                explode(PHP_EOL, file_get_contents($reflection->getFileName())),
                $reflection->getStartLine(),
                $reflection->getEndLine() - $reflection->getStartLine() - 1
            )
        ));

        // Remove the return keyword at the beginning of the snippet.
        if (strpos($snippet, 'return') === 0) {
            $snippet = trim(substr($snippet, 6));
        }

        return $snippet;
    }

    public function getParameters(ReflectionFunction $reflection, $args): array
    {
        return collect($reflection->getParameters())
            ->mapWithKeys(function ($parameter, $index) use ($args) {
                return [$parameter->getName() => $args[$index] ?? $parameter->getDefaultValue()];
            })
            ->all();
    }
}
