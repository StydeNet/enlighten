<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Support\Collection;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\Endpoint;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Models\Module;
use Styde\Enlighten\Models\ModuleCollection;
use Styde\Enlighten\Models\Run;
use Styde\Enlighten\Section;

class ShowAreaController
{
    public function __invoke(Run $run, string $areaSlug = null)
    {
        $action = config('enlighten.area_view', 'features');

        if (! in_array($action, ['features', 'modules', 'endpoints'])) {
            $action = 'features';
        }

        return $this->$action($run, $this->getArea($run, $areaSlug));
    }

    private function modules(Run $run, Area $area = null)
    {
        return view('enlighten::area.modules', [
            'area' => $area,
            'modules' => $this->wrapByModule($this->getGroups($run, $area)->load('stats')),
        ]);
    }

    private function features(Run $run, Area $area = null)
    {
        $groups = $this->getGroups($run, $area)
            ->load([
                'examples' => function ($q) {
                    $q->withCount('queries');
                },
                'examples.group',
                'examples.requests',
                'examples.exception'
            ]);

        return view('enlighten::area.features', [
            'area' => $area,
            'showQueries' => Enlighten::show(Section::QUERIES),
            'groups' => $groups,
        ]);
    }

    private function endpoints(Run $run, Area $area = null)
    {
        $requests = ExampleRequest::query()
            ->select('id', 'example_id', 'request_method', 'request_path')
            ->addSelect('route', 'response_status', 'response_headers')
            ->with([
                'example:id,group_id,title,slug,status',
                'example.group:id,slug,run_id',
            ])
            ->fromRun($run)
            ->get();

        $endpoints = $requests
            ->groupBy('signature')
            ->map(function ($requests) {
                return new Endpoint(
                    $requests->first()->request_method,
                    $requests->first()->route_or_path,
                    $requests, // $requests->sortBy('example.order_num')
                );
            })
            ->sortBy('method_index');

        return view('enlighten::area.endpoints', [
            'area' => $area,
            'modules' => $this->wrapByModule($endpoints),
        ]);
    }

    private function getArea(Run $run, string $areaSlug = null): ?Area
    {
        if (empty($areaSlug)) {
            return null;
        }

        return $run->areas->firstWhere('slug', $areaSlug);
    }

    private function getGroups(Run $run, Area $area = null): Collection
    {
        return $run->groups
            ->when($area, function ($collection, $area) {
                return $collection->where('area', $area->slug);
            });
    }

    private function wrapByModule(Collection $groups): ModuleCollection
    {
        return Module::all()->wrapGroups($groups)->whereHasGroups();
    }
}
