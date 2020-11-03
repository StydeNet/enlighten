<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\ExampleSnippet;

class CodeSnippetExporter
{
    private $currentLevel;

    public function export($snippet): string
    {
        $this->currentLevel = 0;

        return $this->exportValue($snippet);
    }

    private function exportValue($value)
    {
        if (isset($value[ExampleSnippet::CLASS_NAME])) {
            return $this->exportObject($value);
        }

        switch (gettype($value)) {
            case 'array':
                return $this->exportArray($value);
            case 'integer':
                return $this->exportInteger($value);
            case 'double':
            case 'float':
                return $this->exportFloat($value);
            case 'string':
                return $this->exportString($value);
        }

        return '';
    }

    private function exportArray($items)
    {
        $result = $this->exportSymbol('[').$this->exportLine();

        if ($this->isAssoc($items)) {
            $result .= $this->exportAssocArrayItems($items);
        } else {
            $result .= $this->exportArrayItems($items);
        }

        $result .= $this->exportIndentation().$this->exportSymbol(']');

        return $result;
    }

    private function exportAssocArrayItems($items): string
    {
        $result = '';

        $this->currentLevel += 1;

        foreach ($items as $key => $value) {
            $result .= $this->exportIndentation()
                .$this->exportKeyName($key)
                .$this->exportSpace()
                .$this->exportSymbol('=>')
                .$this->exportSpace()
                .$this->exportValue($value)
                .$this->exportLine();
        }

        $this->currentLevel -= 1;

        return $result;
    }

    private function exportArrayItems($items): string
    {
        $result = '';

        $this->currentLevel += 1;

        foreach ($items as $item) {
            $result .= $this->exportIndentation().$this->exportValue($item).$this->exportLine();
        }

        $this->currentLevel -= 1;

        return $result;
    }

    function isAssoc(array $array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function exportSymbol(string $symbol): string
    {
        return "<symbol>{$symbol}</symbol>";
    }

    private function exportInteger(int $snippet): string
    {
        return "<int>{$snippet}</int>";
    }

    private function exportFloat($snippet): string
    {
        return "<float>{$snippet}</float>";
    }

    private function exportString($snippet): string
    {
        return "<string>\"{$snippet}\"</string>";
    }

    private function exportLine(): string
    {
        return PHP_EOL;
    }

    private function exportObject($snippet): string
    {
        $className = $snippet[ExampleSnippet::CLASS_NAME];
        $attributes = $snippet[ExampleSnippet::ATTRIBUTES] ?? [];

        $result = $this->exportClassName($className)
            . $this->exportSpace()
            . $this->exportSymbol('{')
            . $this->exportLine();

        $this->currentLevel += 1;

        foreach ($attributes as $property => $value) {
            $result .= $this->exportIndentation()
                . $this->exportPropertyName($property)
                . $this->exportSymbol(':')
                . $this->exportSpace()
                . $this->exportValue($value)
                . $this->exportLine();
        }

        $this->currentLevel -= 1;

        $result .= $this->exportIndentation().$this->exportSymbol('}');

        return $result;
    }

    private function exportClassName($className): string
    {
        return "<class>{$className}</class>";
    }

    private function exportKeyName(string $key)
    {
        return "<key>{$key}</key>";
    }

    private function exportPropertyName(string $property)
    {
        return "<property>{$property}</property>";
    }

    private function exportIndentation()
    {
        return str_repeat('    ', $this->currentLevel);
    }

    private function exportSpace()
    {
        return ' ';
    }
}
