<?php

namespace Styde\Enlighten\CodeExamples;

abstract class BaseCodeSnippetFormat implements CodeSnippetFormat
{
    public function block(string $code): string
    {
        return implode(PHP_EOL, ['<pre>', $code, '</pre>']);
    }

    public function indentation($level): string
    {
        return str_repeat(' ', $level * 4);
    }

    public function space(): string
    {
        return ' ';
    }

    public function line(): string
    {
        return PHP_EOL;
    }
}
