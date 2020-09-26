<?php

namespace Styde\Enlighten;

class EnlightenController {

    public function index(string $suite = null)
    {
        $tabs = TestSuite::all();

        if ($tabs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        if ($suite === null) {
            $suite = $tabs->first();
        } else {
            $suite = $tabs->firstWhere('slug', $suite);
        }

        if ($suite === null) {
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
        return view('enlighten::example.show', ['group' => $group]);
    }
}
