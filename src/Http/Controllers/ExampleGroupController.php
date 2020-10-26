<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class ExampleGroupController extends Controller
{
    public function show(Run $run, string $group)
    {
        $group = ExampleGroup::where([
            'slug' => $group,
        ])->firstOrFail();

        $group->load(['examples', 'examples.requests']);

        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title
        ]);
    }
}
