<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\ExampleSnippet;

class CodeSnippetExporter
{
    private $currentLevel;

    public function export($snippet): string
    {
        $this->currentLevel = 0;

        return trim($this->doExport($snippet));
    }

    private function doExport($snippet): string
    {
        return $this->exportLine($this->exportValue($snippet));
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
        return $this->exportSymbol('[')
            .PHP_EOL
            .$this->exportArrayItems($items)
            .$this->exportSymbol(']');
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

    private function exportLine($line): string
    {
        return $line.PHP_EOL;
    }

    private function exportObject($snippet): string
    {
        $className = $snippet[ExampleSnippet::CLASS_NAME];
        $attributes = $snippet[ExampleSnippet::ATTRIBUTES] ?? [];

        $result = $this->exportLine(
            $this->exportClassName($className)
            .$this->exportSpace()
            .$this->exportSymbol('{')
        );

        $this->currentLevel += 1;

        foreach ($attributes as $property => $value) {
            $result .= $this->exportLine(
                $this->exportPropertyName($property)
                . $this->exportSymbol(':')
                . $this->exportSpace()
                . $this->exportValue($value)
            );
        }

        $this->currentLevel -= 1;

        $result .= $this->exportSymbol('}');

        return $result;
    }

    private function exportClassName($className): string
    {
        return "<class>{$className}</class>";
    }

    private function exportArrayItems($items): string
    {
        $result = '';

        $this->currentLevel += 1;

        foreach ($items as $item) {
            $result .= $this->doExport($item);
        }

        $this->currentLevel -= 1;

        return $result;
    }

    private function exportPropertyName(string $property)
    {
        return "<property>{$property}</property>";
    }

    private function exportSpace()
    {
        return ' ';
    }
}
