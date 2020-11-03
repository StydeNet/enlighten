<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Contracts\CodeSnippetPrinter;
use Styde\Enlighten\Models\ExampleSnippet;

class CodeSnippetExporter
{
    private $currentLevel;

    /**
     * @var CodeSnippetPrinter
     */
    private $printer;

    public function __construct(CodeSnippetPrinter $printer)
    {
        $this->printer = $printer;
    }

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
                return $this->printer->integer($value);
            case 'double':
            case 'float':
                return $this->printer->float($value);
            case 'string':
                return $this->printer->string($value);
            case 'boolean':
                return $this->printer->bool($value ? 'true' : 'false');
            case 'NULL':
            case 'null':
                return $this->printer->null();
        }

        return '';
    }

    private function exportArray($items)
    {
        $result = $this->printer->symbol('[').$this->printer->line();

        if ($this->isAssoc($items)) {
            $result .= $this->exportAssocArrayItems($items);
        } else {
            $result .= $this->exportArrayItems($items);
        }

        $result .= $this->printer->indentation($this->currentLevel)
            .$this->printer->symbol(']');

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
            $result .= $this->printer->indentation($this->currentLevel)
                .$this->exportValue($key)
                .$this->printer->space()
                .$this->printer->symbol('=>')
                .$this->printer->space()
                .$this->exportValue($value)
                .$this->printer->symbol(',')
                .$this->printer->line();
        }

        $this->currentLevel -= 1;

        return $result;
    }

    private function exportArrayItems($items): string
    {
        $result = '';

        $this->currentLevel += 1;

        foreach ($items as $item) {
            $result .= $this->printer->indentation($this->currentLevel)
                .$this->exportValue($item)
                .$this->printer->symbol(',')
                .$this->printer->line();
        }

        $this->currentLevel -= 1;

        return $result;
    }

    private function exportObject($snippet): string
    {
        $className = $snippet[ExampleSnippet::CLASS_NAME];
        $attributes = $snippet[ExampleSnippet::ATTRIBUTES] ?? [];

        $result = $this->printer->className($className)
            . $this->printer->space()
            . $this->printer->symbol('{')
            . $this->printer->line();

        $this->currentLevel += 1;

        foreach ($attributes as $property => $value) {
            $result .= $this->printer->indentation($this->currentLevel)
                . $this->printer->propertyName($property)
                . $this->printer->symbol(':')
                . $this->printer->space()
                . $this->exportValue($value)
                . $this->printer->symbol(',')
                . $this->printer->line();
        }

        $this->currentLevel -= 1;

        $result .= $this->printer->indentation($this->currentLevel)
            .$this->printer->symbol('}');

        return $result;
    }
}
