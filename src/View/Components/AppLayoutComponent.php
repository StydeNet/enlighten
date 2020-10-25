<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Run;

class AppLayoutComponent extends Component
{
    public $activeRun;

    public function __construct()
    {
        $this->activeRun = $this->getRunFromRequest();
    }

    private function getRunFromRequest()
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

    public function render()
    {
        return view('enlighten::components.app-layout');
    }
    
    public function tabs()
    {
        return Area::all()->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => $value];
        });
    }

    public function runLabel()
    {
        return $this->activeRun->branch.'-'.substr($this->activeRun->head, 0, 8);
    }
}
