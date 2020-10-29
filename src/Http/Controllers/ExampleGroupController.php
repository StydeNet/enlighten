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

        $examples = $group->examples()
            ->with(['group', 'requests', 'exception'])
            ->withCount('queries')
            ->get();

        return view('enlighten::group.show', [
            'group' => $group,
            'examples' => $examples,
            'title' => $group->title
        ]);
    }
}
