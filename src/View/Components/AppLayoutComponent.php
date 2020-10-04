<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\TestSuite;

class AppLayoutComponent extends Component
{

    public function render()
    {
        return view('enlighten::components.app-layout');
    }

    public function activeRun()
    {
        $run = request()->route('run');

        if ($run instanceof Run) {
            return $run;
        } elseif (is_int($run)) {
            return Run::find($run);
        } else {
            return Run::latest()->first();
        }
    }

    public function tabs()
    {
        return TestSuite::all()->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => $value];
        });
    }
}