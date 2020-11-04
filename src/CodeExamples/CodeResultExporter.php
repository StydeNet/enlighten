<?php

namespace Styde\Enlighten\CodeExamples;

use Styde\Enlighten\Models\ExampleSnippet;

class CodeResultExporter
{
    private $currentLevel;

    /**
     * @var CodeSnippetFormat
     */
    private $format;

    public function __construct(CodeSnippetFormat $format)
    {
        $this->format = $format;
    }

    public function export($snippet): string
    {
        $this->currentLevel = 1;

        return $this->format->block(
            $this->exportIndentation()
            . $this->exportValue($snippet)
        );
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
                return $this->format->integer($value);
            case 'double':
            case 'float':
                return $this->format->float($value);
            case 'string':
                return $this->format->string($value);
            case 'boolean':
                return $this->format->bool($value ? 'true' : 'false');
            case 'NULL':
            case 'null':
                return $this->format->null();
        }

        return '';
    }

    private function exportArray($items)
    {
        $result = $this->format->symbol('[').$this->format->line();

        if ($this->isAssoc($items)) {
            $result .= $this->exportAssocArrayItems($items);
        } else {
            $result .= $this->exportArrayItems($items);
        }

        $result .= $this->exportIndentation()
            . $this->format->symbol(']');

        return $result;
    }

    public function isAssoc(array $array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }

    private function exportAssocArrayItems($items): string
    {
        $result = '';

        $this->currentLevel += 1;

        foreach ($items as $key => $value) {
            $result .= $this->exportIndentation()
                . $this->exportValue($key)
                . $this->format->space()
                . $this->format->symbol('=>')
                . $this->format->space()
                . $this->exportValue($value)
                . $this->format->symbol(',')
                . $this->format->line();
        }

        $this->currentLevel -= 1;

        return $result;
    }

    private function exportArrayItems($items): string
    {
        $result = '';

        $this->currentLevel += 1;

        foreach ($items as $item) {
            $result .= $this->exportIndentation()
                . $this->exportValue($item)
                . $this->format->symbol(',')
                . $this->format->line();
        }

        $this->currentLevel -= 1;

        return $result;
    }

    private function exportObject($snippet): string
    {
        $className = $snippet[ExampleSnippet::CLASS_NAME];
        $attributes = $snippet[ExampleSnippet::ATTRIBUTES] ?? [];

        $result = $this->format->className($className)
            . $this->format->space()
            . $this->format->symbol('{')
            . $this->format->line();

        $this->currentLevel += 1;

        foreach ($attributes as $property => $value) {
            $result .= $this->format->indentation($this->currentLevel)
                . $this->format->propertyName($property)
                . $this->format->symbol(':')
                . $this->format->space()
                . $this->exportValue($value)
                . $this->format->symbol(',')
                . $this->format->line();
        }

        $this->currentLevel -= 1;

        $result .= $this->format->indentation($this->currentLevel)
            .$this->format->symbol('}');

        return $result;
    }

    private function exportIndentation(): string
    {
        return $this->format->indentation($this->currentLevel);
    }
}
