<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Statusable;

class StatusBadgeComponent extends Component
{
    use RepresentsStatusAsColor;

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
            'color' => $this->getColor($this->model),
        ]);
    }
}
