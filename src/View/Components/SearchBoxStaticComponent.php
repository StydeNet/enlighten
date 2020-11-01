<?php

namespace Styde\Enlighten\View\Components;

use Illuminate\View\Component;
use Styde\Enlighten\Models\Example;

class SearchBoxStaticComponent extends Component
{
    public function render()
    {
        return view('enlighten::components.search-box-static', [
            'filesIndex' => $this->filesIndex()
        ]);
    }

    private function filesIndex(): string
    {
        $index = Example::query()
            ->with('group')
            ->orderBy('title')
            ->get()
            ->map(function ($example) {
                return [
                    'section' => ucwords($example->group->area) . "/ {$example->group->title}",
                    'title' => $example->title,
                    'url' => $example->url
                ];
            });

        return json_encode($index, JSON_THROW_ON_ERROR);
    }
}
