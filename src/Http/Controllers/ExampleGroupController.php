<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class ExampleGroupController extends Controller
{
    public function show(Run $run, string $area, ExampleGroup $group)
    {
        $group->load(['examples', 'examples.http_data', 'examples.http_data.queries']);

        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title
        ]);
    }
}
