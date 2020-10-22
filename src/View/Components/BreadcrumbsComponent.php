<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;

class BreadcrumbsComponent extends Component
{
    public array $segments;

    public function __construct(array $segments = [])
    {
        $this->segments = $segments;
    }

    public function render()
    {
        return view('enlighten::components.breadcrumbs');
    }
}
