<?php

namespace Styde\Enlighten\Models\Concerns;

use Illuminate\Support\Str;

trait ReadsDynamicAttributes
{
    protected $attributes = [];

    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    public function __isset($name)
    {
        return method_exists($this, 'get'.Str::studly($name)) || array_key_exists($name, $this->attributes);
    }

    public function __get($name)
    {
        if (method_exists($this, $method = 'get'.Str::studly($name))) {
            return $this->$method();
        }

        return $this->attributes[$name] ?? null;
    }
}
