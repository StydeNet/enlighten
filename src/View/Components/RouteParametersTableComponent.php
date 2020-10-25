<?php


namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;

class RouteParametersTableComponent extends Component
{
    public array $parameters;

    public function __construct($parameters = [])
    {
        $this->parameters = $this->normalizeParameters($parameters);
    }

    public function normalizeParameters(array $parameters): array
    {
        return array_map(function ($parameter) {
            $parameter['requirement'] = $parameter['optional'] ? 'Optional' : 'Required';
            return $parameter;
        }, $parameters);
    }

    public function render()
    {
        return view('enlighten::components.route-parameters-table');
    }
}
