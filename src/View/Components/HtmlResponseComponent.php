<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleRequest;

class HtmlResponseComponent extends Component
{
    /**
     * @var ExampleRequest
     */
    public $httpData;

    public function __construct(ExampleRequest $httpData) {
        $this->httpData = $httpData;
    }

    public function render()
    {
        return view('enlighten::components.html-response');
    }
}
