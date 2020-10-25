<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleRequest;

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
            return 'green';
        } elseif ($this->request->response_status > 200 && $this->request->response_status < 400) {
            return 'blue';
        } elseif ($this->request->response_status > 400 && $this->request->response_status < 500) {
            return 'yellow';
        } elseif ($this->request->response_status > 500) {
            return 'red';
        } else {
            return 'gray';
        }
    }

    public function render()
    {
        return view('enlighten::components.response-info', [
            'request' => $this->request,
            'color' => $this->color(),
            'status' => $this->status()
        ]);
    }
}
