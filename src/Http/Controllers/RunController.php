<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Http\Request;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Module;

class RunController extends Controller
{
    public function index()
    {
        return view('enlighten::dashboard.index', [
            'runs' => Run::latest()->get()
        ]);
    }

    public function show(Request $request)
    {
        $tabs = $this->getTabs();
        $run = Run::find($request->route('run'));

        if (empty($run) || $tabs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        if ($request->route('suite') === null) {
            $suite = $tabs->first();
        } else {
            $suite = $tabs->firstWhere('slug', $request->route('suite'));
        }

        if ($suite === null) {
            return redirect(route('enlighten.dashboard'));
        }

        $groups = $run->groups()->with('stats')->bySuite($suite)->get();

        $modules = Module::all();

        $modules->addGroups($groups);

        return view('enlighten::suite.show', [
            'modules' => $modules->whereHasGroups(),
            'title' => 'Dashboard',
            'suite' => $suite
        ]);
    }
}
