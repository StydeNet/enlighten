<?php

namespace Styde\Enlighten\CodeExamples;

use Styde\Enlighten\Models\ExampleSnippet;

class CodeResultExporter
{
    private int $currentLevel;

    public function __construct(private readonly CodeResultFormat $format)
    {
    }

    public function export($snippet)
    {
        $this->currentLevel = 1;

        return $this->format->block(
            $this->exportIndentation()
            . $this->exportValue($snippet)
        );
    }

    private function exportValue($value): string
    {
        if (isset($value[ExampleSnippet::CLASS_NAME])) {
            return $this->exportObject($value);
        }
        return match (gettype($value)) {
            'array' => $this->exportArray($value),
            'integer' => $this->format->integer($value),
            'double', 'float' => $this->format->float($value),
            'string' => $this->format->string($value),
            'boolean' => $this->format->bool($value ? 'true' : 'false'),
            'NULL', 'null' => $this->format->null(),
            default => '',
        };
    }

    private function exportArray($items): string
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

    public function isAssoc(array $array): bool
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
