<?php


namespace Styde\Enlighten\View\Components;


use Illuminate\View\Component;

class RequestInputTableComponent extends Component
{
    /**
     * @var array
     */
    public $input;

    public function __construct(array $input)
    {
        $this->input = $this->normalizeInput($input);
    }

    private function normalizeInput(array $input): array
    {
        return array_map(function ($value) {
            return is_array($value) ? implode(': ', $value) :  $value;
        }, $input);
    }

    public function render()
    {
        return view('enlighten::components.request-input-table');
    }
}
