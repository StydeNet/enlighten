<?php


namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;

class ExampleBreadcrumbs extends Component
{
    public $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    public function render()
    {
        return view('enlighten::components.example-breadcrumbs', [
            'segments' => $this->getBreadcrumbSegments()
        ]);
    }

    private function getBreadcrumbSegments(): array
    {
        return [
            route('enlighten.area.show', ['run' => $this->example->group->run_id, 'area' => $this->example->group->area]) => ucwords((string) $this->example->group->area),
            $this->example->group->url => $this->example->group->title
        ];
    }
}
