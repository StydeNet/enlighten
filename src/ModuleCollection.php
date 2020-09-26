<?php

namespace Styde\Enlighten;

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
                [$matches, $groups] = $groups->partition(fn($group) => $group->matches($module));

                $module->addGroup($matches);
            })
            ->addRemainingGroupsToTheDefaultModule($groups);
    }

    private function addRemainingGroupsToTheDefaultModule(Collection $groups): self
    {
        if ($groups->isEmpty()) {
            return $this;
        }

        $module = new Module(config('enlighten.default_module', 'Other Modules'));

        $module->addGroup($groups);

        return $this->add($module);
    }

    public function whereHasGroups(): self
    {
        return $this->filter(function ($module) {
            return $module->group->isNotEmpty();
        });
    }
}
