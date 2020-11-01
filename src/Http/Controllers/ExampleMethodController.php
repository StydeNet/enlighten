<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;

class ExampleMethodController extends Controller
{
    public function show(string $group, string $method)
    {
        $group =  $this->activeRun()->groups()->where('slug', $group)->firstOrFail();

        $example = $this->getExampleWithRelations($group, $method);

        return view('enlighten::example.show', [
            'example' => $example,
            'example_tabs' => $this->getResponseTabs($example),
            'breadcrumbs' => $this->getBreadcrumbSegments($example)
        ]);
    }

    private function getBreadcrumbSegments(Model $example): array
    {
        return [
            route('enlighten.run.show', ['run' => $example->group->run_id, 'area' => $example->group->area]) => ucwords($example->group->area),
            $example->group->url => $example->group->title
        ];
    }

    private function getResponseTabs(Model $example)
    {
        return $example
            ->requests
            ->map(function ($data, $key) {
                return [
                    'key' => $data->hash,
                    'title' => 'Request ' . ($key + 1),
                    'requests' => $data,
                ];
            });
    }

    private function getExampleWithRelations(ExampleGroup $group, string $method)
    {
        return Example::query()
            ->with('requests', 'requests.queries', 'snippets', 'exception', 'queries', 'group')
            ->where([
                'group_id' => $group->id,
                'method_name' => $method,
            ])
            ->firstOrFail();
    }
}
