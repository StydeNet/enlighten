<?php

namespace Styde\Enlighten\Models;

use Illuminate\Support\Collection;
use Styde\Enlighten\Models\Concerns\GetsStatsFromGroups;
use Styde\Enlighten\Models\Concerns\ReadsDynamicAttributes;

class Module implements Statusable, Statable
{
    use ReadsDynamicAttributes, GetsStatsFromGroups;

    public static function all()
    {
        return ModuleCollection::make(config('enlighten.modules'))
            ->map(function ($item) {
                return new static($item['name'], $item['classes'] ?? [], $item['routes'] ?? []);
            });
    }

    public function __construct(string $name, array $classes = [], array $routes = [])
    {
        $this->setAttributes([
            'name' => $name,
            'classes' => $classes,
            'routes' => $routes,
        ]);
    }

    public function addGroups(Collection $groups): self
    {
        $this->attributes['groups'] = $groups;

        return $this;
    }
}
