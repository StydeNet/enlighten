<?php

namespace Styde\Enlighten\View;

use Illuminate\View\Component;
use Styde\Enlighten\Example;

class StatusBadgeComponent extends Component
{
    public Example $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
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