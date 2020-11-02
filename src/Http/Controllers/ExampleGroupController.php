<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Section;

class ExampleGroupController
{
    public function __invoke(Run $run, string $groupSlug)
    {
        $group =  $run->groups()->where('slug', $groupSlug)->firstOrFail();

        $examples = $group->examples()
            ->with(['group', 'requests', 'exception'])
            ->withCount('queries')
            ->get();

        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title,
            'examples' => $examples,
            'showQueries' => Enlighten::show(Section::QUERIES),
        ]);
    }
}
