<?php

namespace Styde\Enlighten\Contracts;

interface CodeSnippetPrinter
{
    public function symbol(string $symbol): string;

    public function integer(int $value): string;

    public function float($value): string;

    public function string($value): string;

    public function bool($value): string;

    public function null(): string;

    public function line(): string;

    public function className($className): string;

    public function keyName(string $key);

    public function propertyName(string $property);

    public function indentation($level): string;

    public function space(): string;
}
