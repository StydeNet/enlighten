<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Http\Request;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\Run;

class RunController extends Controller
{
    public function show(Request $request, ?Run $run = null)
    {
        $tabs = $this->getTabs();

        if ($request->route('area')) {
            $area = $tabs->firstWhere('slug', $request->route('area'));
            $groups = $run->groups()->with('stats')->filterByArea($area)->get();
        } else {
            $groups = $run->groups()->with('stats')->get();
            $area = null;
        }

        $modules = Module::all();

        $modules->addGroups($groups);

        return view('enlighten::area.show', [
            'modules' => $modules->whereHasGroups(),
            'title' => $area->title ?? 'All Modules',
        ]);
    }
}
