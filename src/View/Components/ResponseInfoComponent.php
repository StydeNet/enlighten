<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;

class ResponseInfoComponent extends Component
{
    public Example $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    public function status()
    {
        return $this->example->http_data->response_status ?? 'UNKNOWN';
    }

    public function color()
    {
        if ($this->example->http_data->response_status == 200) {
            return 'green';
        } elseif ($this->example->http_data->response_status > 200 && $this->example->http_data->response_status < 400) {
            return 'blue';
        } elseif ($this->example->http_data->response_status > 400 && $this->example->http_data->response_status < 500) {
            return 'yellow';
        } elseif ($this->example->http_data->response_status > 500) {
            return 'red';
        } else {
            return 'gray';
        }
    }

    public function render()
    {
        return view('enlighten::components.response-info');
    }
}
