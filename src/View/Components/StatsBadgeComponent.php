<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Statable;

class StatsBadgeComponent extends Component
{
    use RepresentsStatusAsColor;

    private Statable $model;

    public function __construct(Statable $model)
    {
        $this->model = $model;
    }

    public function render()
    {
        return view('enlighten::components.stats-badge', [
            'passingTests' => $this->model->getPassingTestsCount(),
            'testsCount' => $this->model->getTestsCount(),
            'status' => $this->model->getStatus(),
            'color' => $this->getColor($this->model),
        ]);
    }
}
