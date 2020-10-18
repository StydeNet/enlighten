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
            $snippet(...$args),
            $this->getParametersFrom($reflection),
            $args,
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

        // Remove any semicolon at the end of the snippet.
        $snippet = rtrim($snippet, ';');

        return $snippet;
    }

    public function getParametersFrom(ReflectionFunction $reflection): array
    {
        return array_map(function ($parameter) {
            return [
                'name' => $parameter->getName(),
                'type' => optional($parameter->getType())->getName(),
                'nullable' => $parameter->allowsNull(),
                'has_default' => $parameter->isDefaultValueAvailable(),
                'default' => $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null,
            ];
        }, $reflection->getParameters());
    }
}
