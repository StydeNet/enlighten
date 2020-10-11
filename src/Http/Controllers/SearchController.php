<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Run;

class SearchController
{
    public function index(Run $run) {

        $search = request()->query('search');

        $examples = Example::with('group')
            ->whereIn('group_id', $run->groups->pluck('id'))
            ->where('title', 'like', "%$search%")
            ->limit(5)->get();

        return view('enlighten::search.results', [
            'examples' => $examples,
            'run' => $run
        ]);
    }
}