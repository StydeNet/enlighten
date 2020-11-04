<?php

namespace Styde\Enlighten\CodePrinters;

use Styde\Enlighten\Contracts\CodeSnippetPrinter as CodeSnippetFormatterAlias;

abstract class BaseCodeSnippetPrinter implements CodeSnippetFormatterAlias
{
    public function block(string $code): string
    {
        return "<pre>\n{$code}\n</pre>";
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
