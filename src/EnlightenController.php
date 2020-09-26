<?php

namespace Styde\Enlighten;

class EnlightenController {

    public function index()
    {
        $tabs = TestSuite::all();

        $groups = ExampleGroup::with('examples')->get();

        $modules = Module::all();

        $modules->addGroups($groups);

        return view('enlighten::dashboard.index', [
            'modules' => $modules,
            'tabs' => $tabs
        ]);
    }

    public function show(Example $example)
    {
        return view('enlighten::example.show', ['codeExample' => $example]);
    }
}
