<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;

class EnlightenController {

    public function index(string $suite = null)
    {
        $tabs = TestSuite::all()->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => $value];
        });

        if ($tabs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        if ($suite === null) {
            $suite =  $tabs->keys()->first();
        }

        if (! $tabs->has($suite)) {
            return redirect(route('enlighten.dashboard'));
        }

        $groups = ExampleGroup::findByTestSuite($suite);

        $modules = Module::all();

        $modules->addGroups($groups);

        return view('enlighten::dashboard.index', [
            'modules' => $modules->whereHasGroups(),
            'tabs' => $tabs,
            'suite' => $suite
        ]);
    }

    public function show(ExampleGroup $group)
    {
        return view('enlighten::group.show', ['group' => $group]);
    }
}
