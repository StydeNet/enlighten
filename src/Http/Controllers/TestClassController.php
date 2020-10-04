<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class TestClassController extends Controller
{
    public function show(Run $run, string $suite, ExampleGroup $group)
    {
        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title
        ]);
    }
}
