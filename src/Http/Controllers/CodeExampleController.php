<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Models\Run;

class CodeExampleController extends Controller
{
    public function show(Run $run, string $area, ExampleGroup $group, string $method)
    {
        $example = Example::with('http_data', 'exception', 'queries', 'group')
            ->where('method_name', $method)
            ->firstOrFail();

        return view('enlighten::example.show', [
            'example' => $example
        ]);
    }
}
