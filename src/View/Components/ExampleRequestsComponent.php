<?php


namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Section;

class ExampleRequestsComponent extends Component
{
    public $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    public function shouldRender()
    {
        return $this->example->is_http;
    }

    public function render()
    {
        return view('enlighten::components.example-requests', [
            'tabs' => $this->getResponseTabs(),
            'showQueries' => $this->showQueries(),
            'showException' => $this->showException(),
        ]);
    }
    private function showException()
    {
        if (Enlighten::hide(Section::EXCEPTION)) {
            return false;
        }

        return $this->example->has_exception;
    }

    private function showQueries(): bool
    {
        if (Enlighten::hide(Section::QUERIES)) {
            return false;
        }

        return $this->example->queries->isNotEmpty();
    }

    private function getResponseTabs()
    {
        return $this->example
            ->requests
            ->map(function ($request, $key) {
                return (object) [
                    'key' => $request->hash,
                    'title' => sprintf('Request #%s', $key + 1),
                    'request' => $request,
                    'showSession' => Enlighten::show(Section::SESSION) && ! empty($request->session_data),
                ];
            });
    }
}
