<?php

namespace Styde\Enlighten\CodeSnippets;

interface CodeSnippetFormat
{
    public function block(string $code): string;

    public function symbol(string $symbol): string;

    public function integer(int $value): string;

    public function float(float $value): string;

    public function string(string $value): string;

    public function bool(string $value): string;

    public function null(): string;

    public function line(): string;

    public function className(string $className): string;

    public function propertyName(string $property);

    public function indentation(int $level): string;

    public function space(): string;
}
