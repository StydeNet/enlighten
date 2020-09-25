<?php

namespace Styde\Enlighten;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Str;

class ExampleGroupCollection extends EloquentCollection
{
    /**
     * Return a new collection of items with fields that match the given expression.
     *
     * @param string $field
     * @param array|string $expression
     * @return ExampleGroupCollection
     */
    public function match(string $field, $expression)
    {
        return $this->filter(fn($item) => Str::is($expression, $item->getAttribute($field)));
    }
}
