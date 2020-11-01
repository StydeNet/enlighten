<?php

namespace Styde\Enlighten\Http\Controllers;

class ExampleGroupController extends Controller
{
    public function show(string $group)
    {
        $group =  $this->activeRun()->groups()->where('slug', $group)->firstOrFail();

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
