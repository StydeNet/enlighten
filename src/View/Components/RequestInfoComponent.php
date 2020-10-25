<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleRequest;

class RequestInfoComponent extends Component
{
    /**
     * @var ExampleRequest
     */
    private $request;

    public function __construct(ExampleRequest $request)
    {
        $this->request = $request;
    }

    public function render()
    {
        return view('enlighten::components.request-info', [
            'routeInfo' => $this->routeInfo($this->request),
            'request' => $this->request,
            'request_input' => $this->normalizeRequestInput()
        ]);
    }



    private function routeInfo(ExampleRequest $request): array
    {
        return [
            'Method' => $this->request->request_method,
            'Route' => $this->request->route,
            'Example' => $this->request->request_path . ($this->request->request_query_parameters ? '?' . http_build_query($this->request->request_query_parameters) : ''),
        ];
    }

    private function normalizeRequestInput(): array
    {
        return collect($this->request['request_input'])
            ->map(function ($value) {
                return is_array($value) ? json_encode($value, JSON_PRETTY_PRINT) : $value;
            })->toArray();
    }
}
