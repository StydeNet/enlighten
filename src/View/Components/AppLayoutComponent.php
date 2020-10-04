<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\TestSuite;

class AppLayoutComponent extends Component
{
    public $activeRun;

    public function __construct()
    {
        $this->activeRun = $this->activeRun();
    }

    public function render()
    {
        return view('enlighten::components.app-layout');
    }

    public function activeRun()
    {
        $run = request()->route('run');
        if ($run instanceof Run) {
            return $run;
        } elseif (is_numeric($run)) {
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

    public function runLabel()
    {
        return $this->activeRun->branch . ' - ' . substr($this->activeRun->head, 0, 6);
    }
}