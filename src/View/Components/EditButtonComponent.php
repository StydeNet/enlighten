<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;

class EditButtonComponent extends Component
{
    public function __construct(private readonly ?string $file = null)
    {
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
