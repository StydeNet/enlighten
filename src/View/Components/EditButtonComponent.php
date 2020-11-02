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

    public function shouldRender()
    {
        return config('enlighten.developer_mode')
            && ! empty($this->file)
            && ! app()->runningInConsole();
    }

    public function render()
    {
        return view('enlighten::components.edit-button', [
            'file' => $this->file
        ]);
    }
}
