<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Run;

class ExampleGroupController
{
    public function __invoke(Run $run, string $groupSlug)
    {
        $group =  $run->groups()->where('slug', $groupSlug)->firstOrFail();

        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title,
            'examples' => $group->examples()->with(['group', 'requests', 'exception'])->withCount('queries')->get()
        ]);
    }
}
