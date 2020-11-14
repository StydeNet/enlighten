<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\Http\Request;
use Illuminate\View\Component;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\Run;

class AppLayoutComponent extends Component
{
    public $activeRun;

    public function __construct(Request $request)
    {
        $this->activeRun = $request->route('run') ?: Run::latest()->first();
    }

    public function render()
    {
        return view('enlighten::components.app-layout', [
            'showDashboardLink' => ! app()->runningInConsole(),
            'useStaticSearch' => app()->runningInConsole(),
        ]);
    }

    public function tabs()
    {
        return $this->activeRun->areas->map(function ($area) {
            return [
                'slug' => $area->slug,
                'title' => $area->name,
                'active' => $area->slug === request()->route('area'),
                'panels' => $this->panels($area)
            ];
        });
    }

    public function panels(Area $area)
    {
        return Module::all()
            ->wrapGroups(
                $this->activeRun->groups->where('area', $area->slug)
            )->filter(function ($panel) {
                return $panel->groups->isNotEmpty();
            });
    }
}
