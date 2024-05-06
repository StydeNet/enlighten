<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Section;

class RequestInfoComponent extends Component
{
    public function __construct(private ExampleRequest $request)
    {
    }

    public function render()
    {
        return view('enlighten::components.request-info', [
            'routeInfo' => $this->routeInfo($this->request),
            'request' => $this->request,
            'request_input' => $this->normalizeRequestInput(),
            'showRouteParameters' => $this->showRouteParameters(),
            'showInput' => $this->showInput(),
            'showHeaders' => $this->showHeaders(),
        ]);
    }

    private function showRouteParameters()
    {
        if (Settings::hide(Section::ROUTE_PARAMETERS)) {
            return false;
        }

        return ! empty($this->request->route_parameters);
    }

    private function showInput()
    {
        if (Settings::hide(Section::REQUEST_INPUT)) {
            return false;
        }

        return ! empty($this->request->request_input);
    }

    private function showHeaders()
    {
        if (Settings::hide(Section::REQUEST_HEADERS)) {
            return false;
        }

        return ! empty($this->request->request_headers);
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
            ->map(fn ($value) => is_array($value) ? enlighten_json_prettify($value) : $value)->toArray();
    }
}
