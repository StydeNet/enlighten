<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\HttpData;

class HtmlResponseComponent extends Component
{
    /**
     * @var HttpData
     */
    public $httpData;

    public function __construct(HttpData $httpData) {
        $this->httpData = $httpData;
    }

    public function render()
    {
        return view('enlighten::components.html-response');
    }
}
