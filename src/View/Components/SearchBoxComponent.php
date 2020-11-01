<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Run;

class SearchBoxComponent extends Component
{
    public $activeRun;

    public function __construct(Run $run)
    {
        $this->activeRun = $run;
    }

    public function render()
    {
        return view('enlighten::components.search-box', [
            'searchUrl' => route('enlighten.api.search', ['run' => $this->activeRun])
        ]);
    }
}
