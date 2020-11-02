<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\ModuleCollection;
use Styde\Enlighten\Models\Run;

class ShowAreaController
{
    public function __invoke(Run $run, string $areaSlug = null)
    {
        $area = $this->getArea($run, $areaSlug);

        return view('enlighten::area.show', [
            'title' => $area->title ?? 'All Modules',
            'modules' => $this->getModules($this->getGroups($run, $area)),
        ]);
    }

    private function getArea(Run $run, string $areaSlug = null): ?Area
    {
        if (empty($areaSlug)) {
            return null;
        }

        return $run->areas->firstWhere('slug', $areaSlug);
    }

    private function getGroups(Run $run, Area $area = null): Collection
    {
        return $run->groups
            ->when($area, function ($collection, $area) {
                return $collection->where('area', $area->slug);
            })
            ->load('stats');
    }

    private function getModules(Collection $groups): ModuleCollection
    {
        return Module::all()->addGroups($groups)->whereHasGroups();
    }
}
