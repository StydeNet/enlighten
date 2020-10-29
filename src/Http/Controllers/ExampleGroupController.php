<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Run;

class ExampleGroupController extends Controller
{
    public function show(Run $run, string $group)
    {
        $group = $run->groups()
            ->where('slug', $group)
            ->firstOrFail();

        $group->load(['examples', 'examples.requests']);

        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title
        ]);
    }
}
