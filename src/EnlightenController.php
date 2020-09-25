<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;

class EnlightenController {

    public function index()
    {
        $groups = ExampleGroup::with('examples')
            ->get()
            ->groupBy(function ($group) {
                return Str::after(Str::beforeLast($group->class_name, '\\'), '\\');
            });

        return view('enlighten::dashboard.index', [
            'groups' => $groups
        ]);
    }

    public function show(Example $example)
    {
        return view('enlighten::example.show', ['codeExample' => $example]);
    }
}
