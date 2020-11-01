<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Run;

class Controller
{
    protected function activeRun()
    {
        return Run::where('id', request()->route('run'))
            ->firstOr(function () {
                return Run::latest()->first();
            });
    }
}
