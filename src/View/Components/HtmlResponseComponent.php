<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleRequest;

class HtmlResponseComponent extends Component
{
    /**
     * @var ExampleRequest
     */
    public $request;

    public function __construct(ExampleRequest $request) {
        $this->request = $request;
    }

    public function render()
    {
        return view('enlighten::components.html-response');
    }
}
