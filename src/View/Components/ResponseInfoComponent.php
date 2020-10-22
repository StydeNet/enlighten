<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\HttpData;

class ResponseInfoComponent extends Component
{
    /**
     * @var HttpData
     */
    public $httpData;

    public function __construct(HttpData $httpData)
    {
        $this->httpData = $httpData;
    }

    private function status()
    {
        return $this->httpData->response_status ?? 'UNKNOWN';
    }

    private function color()
    {
        if ($this->httpData->response_status == 200) {
            return 'green';
        } elseif ($this->httpData->response_status > 200 && $this->httpData->response_status < 400) {
            return 'blue';
        } elseif ($this->httpData->response_status > 400 && $this->httpData->response_status < 500) {
            return 'yellow';
        } elseif ($this->httpData->response_status > 500) {
            return 'red';
        } else {
            return 'gray';
        }
    }

    public function render()
    {
        return view('enlighten::components.response-info', [
            'http_data' => $this->httpData,
            'color' => $this->color(),
            'status' => $this->status()
        ]);
    }
}
