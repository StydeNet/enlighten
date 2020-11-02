<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Section;

class ShowExampleGroupController
{
    public function __invoke(Run $run, string $groupSlug)
    {
        $group =  $run->findGroup($groupSlug);

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
