<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Module;
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
        return view('enlighten::components.app-layout', [
            'showDashboardLink' => ! app()->runningInConsole()
        ]);
    }

    public function tabs()
    {
        return Area::all()->map(function ($area) {
            return [
                'slug' => $area->slug,
                'title' => $area->title,
                'active' => $area->slug === request()->route('area'),
                'panels' => request()->route('area') ? $this->panels($area) : []
            ];
        });
    }

    public function panels(Area  $area)
    {
        return Module::all()
            ->addGroups(
                $this->getRunFromRequest()->groups()->filterByArea($area)->get()
            )->filter(function ($panel) {
                return $panel->groups->isNotEmpty();
            });
    }

    public function runLabel()
    {
        return $this->activeRun->branch.'-'.$this->activeRun->head;
    }
}
