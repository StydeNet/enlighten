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
        $area = $this->getArea($run, $areaSlug);

        $action = $area->view;

        if (! in_array($action, ['features', 'modules', 'endpoints'])) {
            $action = 'features';
        }

        return $this->$action($run, $area);
    }

    private function modules(Run $run, Area $area)
    {
        return view('enlighten::area.modules', [
            'area' => $area,
            'modules' => $this->wrapByModule($this->getGroups($run, $area)->load('stats')),
        ]);
    }

    private function features(Run $run, Area $area)
    {
        $groups = $this->getGroups($run, $area)
            ->load([
                'examples' => function ($q) {
                    $q->withCount('queries')
                      ->orderBy('order_num');
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

    private function endpoints(Run $run, Area $area)
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
                    $requests->sortBy('example.order_num')
                );
            })
            ->sortBy('method_index');

        return view('enlighten::area.endpoints', [
            'area' => $area,
            'modules' => $this->wrapByModule($endpoints),
        ]);
    }

    private function getArea(Run $run, string $areaSlug = null): Area
    {
        if (empty($areaSlug)) {
            return $this->defaultArea();
        }

        return $run->areas->firstWhere('slug', $areaSlug) ?: $this->defaultArea();
    }

    private function defaultArea(): Area
    {
        return new Area('', trans('enlighten::messages.all_areas'), config('enlighten.area_view', 'features'));
    }

    private function getGroups(Run $run, Area $area): Collection
    {
        // We always want to get the collection with all the groups
        // because we use them to build the menu. So by filtering
        // at a collection level we're actually saving a query.
        return $run->groups
            ->when($area->isNotDefault(), function ($collection) use ($area) {
                return $collection->where('area', $area->slug);
            })
            ->sortBy('order_num');
    }

    private function wrapByModule(Collection $groups): ModuleCollection
    {
        return Module::all()->wrapGroups($groups)->whereHasGroups();
    }
}
