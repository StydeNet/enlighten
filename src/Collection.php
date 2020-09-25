<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;

class Collection extends \Illuminate\Support\Collection
{
    /**
     * Return a collection without the given keys.
     *
     * @param Collection $keys
     * @return Collection
     */
    public function exclude(Collection $keys): Collection
    {
        return $this->diffKeys($keys->flip());
    }

    /**
     * Overwrite the values that are already present in the collection with the given values.
     *
     * @param Collection $values
     * @return Collection
     */
    public function overwrite(Collection $values): Collection
    {
        return $this->merge($values->intersectByKeys($this));
    }

    /**
     * Return a new collection of items with fields that match the given expression.
     *
     * @param string $field
     * @param array|string $expression
     * @return Collection
     */
    public function match(string $field, $expression)
    {
        return $this->filter(fn($item) => Str::is($expression, data_get($item, $field)));
    }
}
