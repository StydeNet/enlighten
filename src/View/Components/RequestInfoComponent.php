<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\HttpData;

class RequestInfoComponent extends Component
{
    private HttpData $httpData;

    public function __construct(HttpData $httpData)
    {
        $this->httpData = $httpData;
    }

    public function render()
    {
        return view('enlighten::components.request-info', [
            'routeInfo' => $this->routeInfo($this->httpData),
            'http_data' => $this->httpData
        ]);
    }

    private function routeInfo(HttpData $httpData): array
    {
        return [
            'Method' => $this->httpData->request_method,
            'Route' => $this->httpData->route,
            'Example' => $this->httpData->request_path . ($this->httpData->request_query_parameters ? '?' . http_build_query($this->httpData->request_query_parameters) : ''),
        ];
    }
}
