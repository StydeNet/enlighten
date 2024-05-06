<?php

namespace Styde\Enlighten\CodeExamples;

use Illuminate\Support\Collection;
use ReflectionFunction;

class CodeInspector
{
    public function getCodeFrom($callback): string
    {
        $reflection = new ReflectionFunction($callback);

        $code = file_get_contents($reflection->getFileName());

        return collect(explode(PHP_EOL, $code))
            ->slice(
                $reflection->getStartLine(),
                $reflection->getEndLine() - $reflection->getStartLine() - 1
            )
            ->pipe(fn($collection) => $this->removeExternalIndentation($collection))
            ->pipe(fn($collection) => $this->removeReturnKeyword($collection))
            ->implode("\n");
    }

    /**
     * Remove the indentation outside the scope of the current code block.
     */
    private function removeExternalIndentation(Collection $lines)
    {
        $leadingSpacesInFirstLine = $this->numberOfLeadingSpaces($lines->first());

        return $lines->transform(fn($line) => preg_replace("/^( {{$leadingSpacesInFirstLine}})/", '', (string) $line));
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
        if (str_starts_with((string) $lines->first(), 'return ')) {
            return $lines->prepend(substr((string) $lines->shift(), 7));
        }

        if (str_starts_with((string) $lines->last(), 'return ')) {
            return $lines->add(substr((string) $lines->pop(), 7));
        }

        return $lines;
    }
}
