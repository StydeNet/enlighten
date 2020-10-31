<?php

namespace Styde\Enlighten;

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
            ->pipe(function ($collection) {
                return $this->removeExternalIndentation($collection);
            })
            ->pipe(function ($collection) {
                return $this->removeReturnKeyword($collection);
            })
            ->implode("\n");
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
