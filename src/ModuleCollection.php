<?php

namespace Styde\Enlighten;

class ModuleCollection extends \Illuminate\Support\Collection
{
    public function getByName($name)
    {
        return $this->firstWhere('name', $name);
    }

    public function addGroups(ExampleGroupCollection $groups) : self
    {
        return $this
            ->each(function ($module) use (&$groups) {
                [$matches, $groups] = $groups->partition(fn($group) => $group->matches($module));

                $module->addGroup($matches);
            })
            ->addRemainingGroupsToTheDefaultModule($groups);
    }

    private function addRemainingGroupsToTheDefaultModule(ExampleGroupCollection $groups): self
    {
        if ($groups->isEmpty()) {
            return $this;
        }

        $module = new Module(config('enlighten.default_module', 'Other Modules'));

        $module->addGroup($groups);

        return $this->add($module);
    }

}
