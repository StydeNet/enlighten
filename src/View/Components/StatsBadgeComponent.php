<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Statable;

class StatsBadgeComponent extends Component
{
    public function __construct(private readonly Statable $model)
    {
    }

    public function render()
    {
        return view('enlighten::components.stats-badge', [
            'positive' => $this->model->getPassingTestsCount(),
            'total' => $this->model->getTestsCount(),
            'color' => $this->model->getStatus(),
        ]);
    }
}
