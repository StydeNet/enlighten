<?php

namespace Styde\Enlighten\Models;

trait ReplacesValues
{
    /**
     * Returns an array of values without the ignored keys and
     * overwriting the given $values with the $overwrite values.
     *
     * @param array|string|null $values
     * @param array $config
     * @return array
     */
    public function replaceValues($values, array $config): array
    {
        if (is_null($values)) {
            return [];
        }

        if (is_string($values)) {
            $values = json_decode($values, JSON_OBJECT_AS_ARRAY);
        }

        return collect($values)
            ->merge(array_intersect_key($config['overwrite'] ?? [], $values))
            ->diffKeys(array_flip($config['hide'] ?? []))
            ->all();
    }
}
