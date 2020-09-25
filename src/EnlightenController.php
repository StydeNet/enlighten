<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;

class EnlightenController {

    public function index()
    {
        $groups = ExampleGroup::with('examples')->get();

        $tabs = $groups->getTestSuites();

        $modules = ModuleCollection::make(config('enlighten.modules'));

        return view('enlighten::dashboard.index', [
            'modules' => $modules->addGroups($groups),
            'tabs' => $tabs
        ]);
    }

    public function show(Example $example)
    {
        return view('enlighten::example.show', ['codeExample' => $example]);
    }
}
