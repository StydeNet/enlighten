<?php

namespace Styde\Enlighten;

use Closure;
use Illuminate\Support\Collection;
use ReflectionFunction;

class CodeInspector
{
    public function getInfoFrom(Closure $snippet, array $args = [])
    {
        $reflection = new ReflectionFunction($snippet);

        return new CodeSnippet(
            $this->getCodeBlock($reflection),
            $this->getParameters($reflection, $args),
        );
    }

    public function getCodeBlock(ReflectionFunction $reflection): string
    {
        return collect(
                explode(PHP_EOL, file_get_contents($reflection->getFileName()))
            )
            ->slice(
                $reflection->getStartLine(),
                $reflection->getEndLine() - $reflection->getStartLine() - 1
            )
            ->pipe(function ($collection) {
                return $this->removeExternalIndentation($collection);
            })
            ->pipe(function ($collection) {
                return $this->removeReturnKeyword($collection);
            })
            ->implode("\n");
    }

    public function getParameters(ReflectionFunction $reflection, $args): array
    {
        return collect($reflection->getParameters())
            ->mapWithKeys(function ($parameter, $index) use ($args) {
                return [$parameter->getName() => $args[$index] ?? $parameter->getDefaultValue()];
            })
            ->all();
    }

    /**
     * Remove the indentation outside the scope of the current code block.
     */
    private function removeExternalIndentation(Collection $lines)
    {
        $leadingSpacesInFirstLine = $this->numberOfLeadingSpaces($lines->first());

        return $lines->transform(function ($line) use ($leadingSpacesInFirstLine) {
            return preg_replace("/^( {{$leadingSpacesInFirstLine}})/", '', $line);
        });
    }

    private function numberOfLeadingSpaces(string $str)
    {
        preg_match('/^( +)/', $str, $matches);

        return strlen($matches[1]);
    }

    /**
     * Remove the return keyword in the first or in the last line of the code block.
     */
    private function removeReturnKeyword(Collection $lines)
    {
        if (strpos($lines->first(), 'return ') === 0) {
            return $lines->prepend(substr($lines->shift(), 7));
        }

        if (strpos($lines->last(), 'return ') === 0) {
            return $lines->add(substr($lines->pop(), 7));
        }

        return $lines;
    }
}
