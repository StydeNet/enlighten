<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Statusable;

class StatusBadgeComponent extends Component
{
    public Statusable $model;
    public string $size;

    public function __construct(Statusable $model, string $size = '8')
    {
        $this->model = $model;
        $this->size = $size;
    }

    public function render()
    {
        return view('enlighten::components.status-badge', [
            'color' => $this->color(),
        ]);
    }

    private function color()
    {
        if ($this->model->hasPassed()) {
            return 'green';
        } elseif ($this->model->hasFailed()) {
            return 'red';
        } else {
            return 'yellow';
        }
    }
}
