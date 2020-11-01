<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Run;

class RunController
{
    public function index()
    {
        $runs = Run::query()->with('stats')->latest()->get();

        if ($runs->isEmpty()) {
            return redirect(route('enlighten.intro'));
        }

        return view('enlighten::run.index', ['runs' => $runs]);
    }
}
