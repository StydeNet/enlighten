<?php

namespace Styde\Enlighten;

trait ReplacesValues
{
    /**
     * Returns an array of values without the excluded keys and
     * overwriting the given $values with the $overwrite values.
     *
     * @param array $values
     * @param array $excluded
     * @param array $overwrite
     * @return array
     */
    public function replaceValues(array $values, array $excluded, array $overwrite): array
    {
        return collect($values)
            ->merge(array_intersect_key($overwrite, $values))
            ->diffKeys(array_flip($excluded))
            ->all();
    }
}
