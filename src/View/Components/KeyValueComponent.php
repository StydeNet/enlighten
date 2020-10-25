<?php


namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;

class KeyValueComponent extends Component
{
    /**
     * @var array
     */
    public $items;

    /**
     * @var string|null
     */
    public $title;

    public function __construct(array $items, ?string $title = null)
    {
        $this->items = $this->normalizeItems($items);

        $this->title = $title;
    }

    private function normalizeItems(array $items): array
    {
        return array_map(function ($value) {
            return is_array($value) ? implode('<br/>', $value) : $value;
        }, $items);
    }

    public function render()
    {
        return view('enlighten::components.key-value');
    }
}
