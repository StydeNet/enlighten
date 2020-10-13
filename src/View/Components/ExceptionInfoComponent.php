<?php


namespace Styde\Enlighten\View\Components;


use Illuminate\Support\Str;
use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleException;

class ExceptionInfoComponent extends Component
{
    private ExampleException $exception;

    public function __construct(ExampleException $exception)
    {
        $this->exception = $exception;
    }

    private function trace()
    {
        if (!is_array($this->exception->trace)) {
            return collect();
        }

        return collect($this->exception->trace)->filter(function ($trace) {
            return !@Str::contains($trace['file'], DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR);
        })->map(function ($data) {
            return [
                'file' => $data['file'] ?? '',
                'line' => $data['line'] ?? '',
                'function' => $data['function'],
                'args' => json_encode($data['args'])
            ];
        });
    }

    public function render()
    {
        if ($this->trace()->isEmpty()) {
            return;
        }

        return view('enlighten::components.exception-info', [
            'trace' => $this->trace(),
            'exception' => $this->exception
        ]);
    }
}