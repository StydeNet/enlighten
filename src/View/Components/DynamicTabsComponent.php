<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\View\Component;
use Illuminate\View\ComponentSlot;

class DynamicTabsComponent extends Component
{
    private $tabs;
    public $htmlable;
    public $type;

    public function __construct(array $tabs, string $type = 'pills')
    {
        $this->tabs = $this->normalizeTabs($tabs);
        $this->type = $type;
        $this->htmlable = class_exists(ComponentSlot::class) ? ComponentSlot::class : HtmlString::class;
    }

    public function render()
    {
        if ($this->type === 'menu') {
            $view = 'enlighten::components.dynamic-tabs-menu';
        } else {
            $view = 'enlighten::components.dynamic-tabs';
        }

        return view($view, [
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
