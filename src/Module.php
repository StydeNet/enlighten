<?php

namespace Styde\Enlighten;

use Illuminate\Support\Collection;

class Module
{
    public string $name;
    /**
     * @var string|array
     */
    public $pattern;

    public Collection $group;

    public static function all()
    {
        return ModuleCollection::make(config('enlighten.modules'))
            ->map(function ($item) {
                return new static($item['name'], $item['pattern']);
            });
    }

    public function __construct(string $name, $pattern = [])
    {
        $this->name = $name;
        $this->pattern = $pattern;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array|string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    public function addGroup(Collection $group): void
    {
        $this->group = $group;
    }

    public function getGroup(): Collection
    {
        return $this->group;
    }
}
