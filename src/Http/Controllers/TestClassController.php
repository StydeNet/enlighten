<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class TestClassController extends Controller
{
    public function show(Run $run, string $area, $group)
    {
        $tabs = $this->getTabs();
        $area = $tabs->firstWhere('slug', $area);

        $group = ExampleGroup::filterByArea($area)->where('slug',$group)->first();
        $group->load(['examples', 'examples.http_data']);

        return view('enlighten::group.show', [
            'group' => $group,
            'title' => $group->title
        ]);
    }
}
