<?php

namespace Styde\Enlighten;

class Module
{
    public string $name;
    /**
     * @var string|array
     */
    public $pattern;

    public ExampleGroupCollection $group;

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

    public function addGroup(ExampleGroupCollection $group): void
    {
        $this->group = $group;
    }

    public function getGroup(): ExampleGroupCollection
    {
        return $this->group;
    }
}
