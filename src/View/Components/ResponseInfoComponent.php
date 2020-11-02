<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\ExampleRequest;
use Styde\Enlighten\Section;

class ResponseInfoComponent extends Component
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
        return view('enlighten::components.response-info', [
            'request' => $this->request,
            'color' => $this->request->getStatus(),
            'status' => $this->request->response_status ?? 'UNKNOWN',
            'showHeaders' => $this->showHeaders(),
        ]);
    }

    private function showHeaders()
    {
        if (Enlighten::hide(Section::RESPONSE_HEADERS)) {
            return false;
        }

        return ! empty($this->request->response_headers);
    }
}
