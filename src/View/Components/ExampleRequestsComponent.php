<?php


namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;

class ExampleRequestsComponent extends Component
{
    public $example;

    public function __construct(Example $example)
    {
        $this->example=  $example;
    }

    public function shouldRender()
    {
        return $this->example->is_http;
    }

    public function render()
    {
        return view('enlighten::components.example-requests', [
            'tabs' => $this->getResponseTabs()
        ]);
    }

    private function getResponseTabs()
    {
        return $this->example
            ->requests
            ->map(function ($data, $key) {
                return [
                    'key' => $data->hash,
                    'title' => 'Request ' . ($key + 1),
                    'requests' => $data,
                ];
            });
    }
}
