<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Section;

class HtmlResponseComponent extends Component
{
    /**
     * @var ExampleRequest
     */
    public $request;

    public function __construct(ExampleRequest $request)
    {
        $this->request = $request;
    }

    public function render()
    {
        return view('enlighten::components.html-response', [
            'showHtml' => Enlighten::show(Section::HTML),
            'showTemplate' => $this->showTemplate(),
        ]);
    }

    private function showTemplate(): bool
    {
        if (Enlighten::hide(Section::BLADE)) {
            return false;
        }

        return ! empty($this->request->response_template);
    }
}
