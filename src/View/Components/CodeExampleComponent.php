<?php


namespace Styde\Enlighten\View\Components;


use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Status;

class CodeExampleComponent extends Component
{
    public Example $example;

    public function __construct(Example $example)
    {
        $this->example = $example;
    }

    public function render()
    {
        return view('enlighten::group._code-example', [
            'developer_mode' => config('enlighten.developer-mode'),
            'failed' => $this->example->getStatus() !== Status::SUCCESS,
        ]);
    }
}