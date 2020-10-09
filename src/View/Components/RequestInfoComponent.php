<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;

class RequestInfoComponent extends Component
{
    public Example $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    public function render()
    {
        return view('enlighten::components.request-info', [
            'routeInfo' => $this->routeInfo($this->example)
        ]);
    }

    private function routeInfo(Example $example): array
    {
        return [
            'Method' => $example->http_data->request_method,
            'Route' => $example->http_data->route,
            'Example' => $example->http_data->request_path . ($example->http_data->request_query_parameters ? '?' . http_build_query($example->http_data->request_query_parameters) : ''),
        ];
    }
}
