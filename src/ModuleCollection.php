<?php

namespace Styde\Enlighten;

class ModuleCollection extends \Illuminate\Support\Collection
{
    public function __construct($items = [])
    {
        parent::__construct(array_map(function ($item) {
            return new Module($item['name'], $item['pattern']);
        }, $items));
    }

    public function getByName($name)
    {
        return $this->first(function ($module) use ($name) {
            return $module->getName() === $name;
        });
    }

    public function addGroups(ExampleGroupCollection $groups)
    {
        $this->each(function ($module) use ($groups) {
            $matches = $groups->match('class_name', $module->getPattern());

            $module->addGroup($matches);

            // Avoid adding the same group twice to the module collection.
            $groups->forget($matches->keys()->all());
        });

        $this->addRemainingGroupsToTheDefaultModule($groups);
    }

    private function addRemainingGroupsToTheDefaultModule(ExampleGroupCollection $groups)
    {
        if ($groups->isEmpty()) {
            return;
        }

        $module = new Module(config('enlighten.default_moudle', 'Other Modules'));

        $module->addGroup($groups);

        $this->add($module);
    }

}
