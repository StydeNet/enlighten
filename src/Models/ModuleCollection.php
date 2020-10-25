<?php

namespace Styde\Enlighten\Models;

use Illuminate\Support\Collection;

class ModuleCollection extends Collection
{
    public function getByName($name)
    {
        return $this->firstWhere('name', $name);
    }

    public function addGroups(Collection $groups) : self
    {
        return $this
            ->each(function ($module) use (&$groups) {
                [$matches, $groups] = $groups->partition(function ($group) use ($module) {
                    return $group->matches($module);
                });
                $module->addGroups($matches);
            })
            ->addRemainingGroupsToTheDefaultModule($groups);
    }

    private function addRemainingGroupsToTheDefaultModule(Collection $groups): self
    {
        if ($groups->isEmpty()) {
            return $this;
        }

        $module = new Module(config('enlighten.default_module', 'Other Modules'));

        return $this->add($module->addGroups($groups));
    }

    public function whereHasGroups(): self
    {
        return $this->filter(function ($module) {
            return $module->groups->isNotEmpty();
        });
    }
}
