<?php

namespace Styde\Enlighten\Models;

use Illuminate\Support\Str;
use Symfony\Component\VarDumper\VarDumper;

class OutputFormatter
{
    const CLASS_NAME = '--class_name';
    const ATTRIBUTES = '--attributes';

    private $classReferences = [];

    private function mapObjectAttributes($attrs)
    {
        if (!isset($attrs[static::CLASS_NAME]) && is_array($attrs)) {
            return array_map([$this, 'mapObjectAttributes'], $attrs);
        }

        if (!isset($attrs[static::CLASS_NAME] )) {
            return $attrs;
        }

        $className = 'class__'.Str::random(30);

        $this->classReferences[$className] = $attrs[static::CLASS_NAME];

        eval("class $className { }");
        $class = new $className;

        $attributes = array_map([$this, 'mapObjectAttributes'], $attrs[static::ATTRIBUTES]);

        foreach ($attributes as $name => $value) {
            $class->{$name} = $value;
        }

        return $class;
    }

    private function isResultObject($value): bool
    {
        return isset($value[static::CLASS_NAME]);
    }


    public function applyFormat($result): string
    {
        // we need to wrap the object a the top level to make the recursive call consistent
        if ($this->isResultObject($result)) {
            $output = array_map([$this, 'mapObjectAttributes'], [$result])[0];
        } elseif(is_array($result)) {
            $output = array_map([$this, 'mapObjectAttributes'], $result);
        } else {
            return $this->getDump($result);
        }

        $output = $this->getDump($output);

        $output = str_replace(array_keys($this->classReferences), $this->classReferences, $output);

        return $output;
    }

    private function getDump($output)
    {
        ob_start();
        VarDumper::dump($output);
        return ob_get_clean();
    }

    public static function format($result): string
    {
        return (new static)->applyFormat($result);
    }
}
