<?php

namespace Styde\Enlighten\View;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;

class StatusBadgeComponent extends Component
{
    public Example $example;
    public string $size;

    public function __construct(Example $example, string $size = '8')
    {
        $this->example = $example;
        $this->size = $size;
    }

    public function color()
    {
        if ($this->example->passed) {
            return 'green';
        } elseif ($this->example->failed) {
            return 'red';
        } else {
            return 'yellow';
        }
    }

    public function render()
    {
        return view('enlighten::components.status-badge');
    }
}
