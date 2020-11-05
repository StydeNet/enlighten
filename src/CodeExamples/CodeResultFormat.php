<?php

namespace Styde\Enlighten\CodeExamples;

interface CodeResultFormat
{
    public function block(string $code);

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
