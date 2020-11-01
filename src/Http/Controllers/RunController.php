<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Http\Request;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\Run;

class RunController
{
    public function index()
    {
        $runs = Run::query()->with('stats')->latest()->get();

        if ($runs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        return view('enlighten::run.index', ['runs' => $runs]);
    }

    public function show(Run $run, Request $request)
    {
        if ($request->route('area')) {
            $area = Area::all()->firstWhere('slug', $request->route('area'));
            $groups = $run->groups()->with('stats')->filterByArea($area)->get();
        } else {
            $groups = $run->groups()->with('stats')->get();
            $area = null;
        }

        return view('enlighten::area.show', [
            'modules' => Module::all()->addGroups($groups)->whereHasGroups(),
            'title' => $area->title ?? 'All Modules',
        ]);
    }
}
