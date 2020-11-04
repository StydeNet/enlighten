<?php

namespace Styde\Enlighten\CodePrinters;

class HtmlPrinter extends BaseCodeSnippetPrinter
{
    public function symbol(string $symbol): string
    {
        return "<span class=\"enlighten-symbol\">{$symbol}</span>";
    }

    public function integer(int $value): string
    {
        return "<int>{$value}</int>";
    }

    public function float($value): string
    {
        return "<float>{$value}</float>";
    }

    public function string($value): string
    {
        return sprintf('<string>"%s"</string>', $value);
    }

    public function className($className): string
    {
        return "<class>{$className}</class>";
    }

    public function propertyName(string $property)
    {
        return "<property>{$property}</property>";
    }

    public function bool($value): string
    {
        return "<bool>{$value}</bool>";
    }

    public function null(): string
    {
        return '<null>null</null>';
    }
}
