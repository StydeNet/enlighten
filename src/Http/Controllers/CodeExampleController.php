<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class CodeExampleController extends Controller
{
    public function show(Run $run, string $area, ExampleGroup $group, string $method)
    {
        $example = Example::with('http_data', 'snippets', 'exception', 'queries', 'group')
            ->where('method_name', $method)
            ->firstOrFail();

        $responseTabs = $example->http_data->map(function ($data, $key) {
            return [
                'key' => $data->hash,
                'title' => 'Request '. ($key + 1),
                'http_data' => $data
            ];
        });

        return view('enlighten::example.show', [
            'example' => $example,
            'example_tabs' => $responseTabs
        ]);
    }
}
