<?php

namespace Styde\Enlighten\View\Widgets;

use Illuminate\Contracts\Support\Responsable;
use Styde\Enlighten\Models\Run;

class RunsTableWidget implements Responsable
{
    public function toResponse($request)
    {
        return view('enlighten::widgets.runs-table', [
            'runs' =>  Run::query()->with('groups', 'groups.stats')->latest()->get()
        ]);
    }
}
