<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;

class EditButtonComponent extends Component
{
    /**
     * @var string|null
     */
    private $file;

    public function __construct(?string $file = null)
    {
        $this->file = $file;
    }

    public function render()
    {
        if (!config('enlighten.developer_mode') && !$this->file) {
            return '';
        }

        return view('enlighten::components.edit-button', [
            'file' => $this->file
        ]);
    }
}
