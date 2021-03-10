<?php

namespace Styde\Enlighten\Models;

trait ReplacesValues
{
    /**
     * Returns an array of values without the ignored keys and
     * overwriting the given $values with the $overwrite values.
     *
     * @param array|string|null $originalValues
     * @param array $config
     * @return mixed
     */
    public function replaceValues($originalValues, array $config)
    {
        $decodedValues = $this->decodeValues($originalValues);

        if (! is_array($decodedValues)) {
            return $originalValues;
        }

        return collect($decodedValues)
            ->merge(array_intersect_key($config['overwrite'] ?? [], $decodedValues))
            ->diffKeys(array_flip($config['hide'] ?? []))
            ->all();
    }

    private function decodeValues($originalValues)
    {
        if (! is_string($originalValues)) {
            return $originalValues;
        }

        return json_decode($originalValues, JSON_OBJECT_AS_ARRAY);
    }
}
