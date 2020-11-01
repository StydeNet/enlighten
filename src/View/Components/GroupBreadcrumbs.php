<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\ExampleGroup;

class GroupBreadcrumbs extends Component
{
    public $exampleGroup;

    public function __construct(ExampleGroup $exampleGroup)
    {
        $this->exampleGroup = $exampleGroup;
    }

    public function render()
    {
        return view('enlighten::components.group-breadcrumbs', [
            'segments' => $this->getBreadcrumbsSegments()
        ]);
    }

    private function getBreadcrumbsSegments(): array
    {
        return [
            route('enlighten.area.show', [
                'run' => $this->exampleGroup->run_id,
                'area' => $this->exampleGroup->area
            ]) => ucwords($this->exampleGroup->area),
        ];
    }
}
