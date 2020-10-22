<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\Support\Collection;
use Illuminate\View\Component;

class DynamicTabsComponent extends Component
{
    private Collection $tabs;

    public function __construct(array $tabs)
    {
        $this->tabs = $this->normalizeTabs($tabs);
    }

    public function render()
    {
        return view('enlighten::components.dynamic-tabs', [
            'tabs_collection' => $this->tabs
        ]);
    }

    private function normalizeTabs(array $tabs): Collection
    {
        return collect($tabs)->mapWithKeys(function ($value, $key) {

            if (is_numeric($key)) {
                return [strtolower($value) => $value];
            }

            return [$key => $value];
        });
    }
}
