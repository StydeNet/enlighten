<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class CodeExampleController extends Controller
{
    public function show(Run $run, ExampleGroup $group, string $method)
    {
        $example = Example::with('requests', 'requests.queries', 'snippets', 'exception', 'queries', 'group')
            ->where([
                'group_id' => $group->id,
                'method_name' => $method,
            ])
            ->firstOrFail();

        $responseTabs = $example->requests->map(function ($data, $key) {
            return [
                'key' => $data->hash,
                'title' => 'Request '. ($key + 1),
                'requests' => $data,
            ];
        });

        return view('enlighten::example.show', [
            'example' => $example,
            'example_tabs' => $responseTabs
        ]);
    }
}
