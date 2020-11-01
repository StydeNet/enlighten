<?php

namespace Styde\Enlighten\Http\Controllers;


use Illuminate\Http\Request;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\Run;

class AreaController
{
    public function __invoke(Run $run, Request $request)
    {
        $groups = $run->groups()->with('stats');

        if ($request->route('area')) {
            $area = Area::all()->firstWhere('slug', $request->route('area'));
            $groups = $groups->filterByArea($area);
        }

        return view('enlighten::area.show', [
            'modules' => Module::all()->addGroups($groups->get())->whereHasGroups(),
            'title' => $area->title ?? 'All Modules',
        ]);
    }
}
