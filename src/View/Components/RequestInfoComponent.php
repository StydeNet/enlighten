<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleRequest;

class RequestInfoComponent extends Component
{
    /**
     * @var ExampleRequest
     */
    private $httpData;

    public function __construct(ExampleRequest $httpData)
    {
        $this->httpData = $httpData;
    }

    public function render()
    {
        return view('enlighten::components.request-info', [
            'routeInfo' => $this->routeInfo($this->httpData),
            'http_data' => $this->httpData,
            'request_input' => $this->normalizeRequestInput()
        ]);
    }



    private function routeInfo(ExampleRequest $httpData): array
    {
        return [
            'Method' => $this->httpData->request_method,
            'Route' => $this->httpData->route,
            'Example' => $this->httpData->request_path . ($this->httpData->request_query_parameters ? '?' . http_build_query($this->httpData->request_query_parameters) : ''),
        ];
    }

    private function normalizeRequestInput(): array
    {
        return collect($this->httpData['request_input'])
            ->map(function ($value) {
                return is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
            })->toArray();
    }
}
