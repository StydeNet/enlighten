<?php

namespace Styde\Enlighten\Models;

use Illuminate\Support\Collection;

class ModuleCollection extends Collection
{
    public function getByName($name)
    {
        return $this->firstWhere('name', $name);
    }

    public function wrapGroups(Collection $groups) : self
    {
        return $this
            ->each(function ($module) use (&$groups) {
                [$matches, $groups] = $groups->partition(fn (Wrappable $group) => $group->matches($module));

                $module->addGroups($matches);
            })
            ->wrapRemainingGroups($groups);
    }

    private function wrapRemainingGroups(Collection $groups): self
    {
        if ($groups->isEmpty()) {
            return $this;
        }

        $module = new Module(config('enlighten.default_module', 'Other Modules'));

        return $this->add($module->addGroups($groups));
    }

    public function whereHasGroups(): self
    {
        return $this->filter(fn ($module) => $module->groups->isNotEmpty());
    }
}
