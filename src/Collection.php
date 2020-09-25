<?php

namespace Styde\Enlighten;

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
}
