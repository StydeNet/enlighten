<?php

namespace Styde\Enlighten\CodeExamples;

use Illuminate\Support\HtmlString;

class PlainCodeResultFormat extends BaseCodeResultFormat
{
    public function block(string $code): HtmlString
    {
        return new HtmlString('<pre>'.$code.'</pre>');
    }

    public function symbol(string $symbol): string
    {
        return $symbol;
    }

    public function integer(int $value): string
    {
        return $value;
    }

    public function float(float $value): string
    {
        return $value;
    }

    public function string(string $value): string
    {
        return sprintf('"%s"', $value);
    }

    public function bool(string $value): string
    {
        return strtoupper($value);
    }

    public function null(): string
    {
        return 'NULL';
    }

    public function className(string $name): string
    {
        return $name;
    }

    public function propertyName(string $name): string
    {
        return $name;
    }

    public function indentation($level): string
    {
        return '';
    }

    public function space(): string
    {
        return ' ';
    }

    public function line(): string
    {
        return ' ';
    }
}
