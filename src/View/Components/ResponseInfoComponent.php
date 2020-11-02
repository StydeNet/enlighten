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

    private function status()
    {
        return $this->request->response_status ?? 'UNKNOWN';
    }

    private function color()
    {
        if ($this->request->response_status === 200) {
            return 'success';
        } elseif ($this->request->response_status > 200 && $this->request->response_status < 400) {
            return 'default';
        } elseif ($this->request->response_status > 400 && $this->request->response_status < 500) {
            return 'warning';
        } elseif ($this->request->response_status > 500) {
            return 'failure';
        } else {
            return 'default';
        }
    }

    public function render()
    {
        return view('enlighten::components.response-info', [
            'request' => $this->request,
            'color' => $this->color(),
            'status' => $this->status(),
            'showHeaders' => $this->showHeaders(),
        ]);
    }

    private function showHeaders()
    {
        if (Enlighten::hide(Section::RESPONSE_HEADERS)) {
            return false;
        }

        return ! empty ($this->request->response_headers);
    }
}
