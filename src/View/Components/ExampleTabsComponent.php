<?php


namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Section;

class ExampleTabsComponent extends Component
{
    public $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    public function render()
    {
        return view('enlighten::components.example-tabs', [
            'showRequests' => $this->example->requests->isNotEmpty(),
            'requestTabs' => $this->getRequestTabs(),
            'showQueries' => $this->shouldShowQueries(),
            'showException' => $this->showException(),
        ]);
    }
    private function showException()
    {
        if (Settings::hide(Section::EXCEPTION)) {
            return false;
        }

        return $this->example->has_exception;
    }

    private function shouldShowQueries(): bool
    {
        if (Settings::hide(Section::QUERIES)) {
            return false;
        }

        return $this->example->queries->isNotEmpty();
    }

    private function getRequestTabs()
    {
        return $this->example->requests->map(fn($request, $key) => $this->newRequestTab($request, $key + 1));
    }

    private function newRequestTab(ExampleRequest $request, int $requestNumber): object
    {
        return (object) [
            'request' => $request,
            'key' => $request->hash,
            'title' => "Request #{$requestNumber}",
            'showSession' => $this->shouldShowSessionTab($request),
            'showPreviewOnly' => $this->shouldOnlyShowPreview($request),
        ];
    }

    private function shouldShowSessionTab(ExampleRequest $request): bool
    {
        return Settings::show(Section::SESSION) && !empty($request->session_data);
    }

    private function shouldOnlyShowPreview(ExampleRequest $request): bool
    {
        return $this->example->has_exception && $request->response_type === 'JSON';
    }
}
