<?php

namespace Styde\Enlighten\CodeSnippets;

class HtmlPrinter extends BaseCodeSnippetFormat
{
    public function symbol(string $symbol): string
    {
        return "<span class=\"enlighten-symbol\">{$symbol}</span>";
    }

    public function integer(int $value): string
    {
        return "<span class=\"enlighten-int\">{$value}</span>";
    }

    public function float($value): string
    {
        return "<span class=\"enlighten-float\">{$value}</span>";
    }

    public function string($value): string
    {
        return sprintf('<span class="enlighten-string">"%s"</span>', $value);
    }

    public function className($className): string
    {
        return "<span class=\"enlighten-class\">{$className}</span>";
    }

    public function propertyName(string $property)
    {
        return "<span class=\"enlighten-property\">{$property}</span>";
    }

    public function bool($value): string
    {
        return "<span class=\"enlighten-bool\">{$value}</span>";
    }

    public function null(): string
    {
        return '<span class=\"enlighten-null\">null</span>';
    }
}
