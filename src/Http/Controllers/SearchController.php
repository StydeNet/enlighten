<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Http\Request;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Run;

class SearchController
{
    public function __invoke(Run $run, Request $request)
    {
        $examples = $this->getExamples($run, $request->query('search'));

        return view('enlighten::search.results', [
            'examples' => $examples,
            'run' => $run
        ]);
    }

    private function getExamples($run, $search)
    {
        return Example::query()
            ->with('group')
            ->whereHas('group.run', function ($q) use ($run) {
                $q->where('id', $run->id);
            })
            ->where('title', 'like', "%$search%")
            ->limit(5)
            ->get();
    }
}
