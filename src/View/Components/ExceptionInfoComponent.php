<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleException;

class ExceptionInfoComponent extends Component
{
    public function __construct(private readonly ExampleException $exception)
    {
    }

    private function trace()
    {
        if (!is_array($this->exception->trace)) {
            return collect();
        }

        return collect($this->exception->trace)
            ->map(fn ($data) => [
                'file' => $data['file'] ?? '',
                'line' => $data['line'] ?? '',
                'function' => $this->getFunctionSignature($data),
                'args' => $data['args'] ?? [],
            ]);
    }

    private function getFunctionSignature(array $data): string
    {
        if (empty($data['class'])) {
            return $data['function'];
        }

        return $data['class'].$data['type'].$data['function'];
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
