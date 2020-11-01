<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Run;

class ExampleMethodController
{
    public function show(Run $run, string $groupSlug, string $method)
    {
        $group =  $run->groups()->where('slug', $groupSlug)->firstOrFail();

        return view('enlighten::example.show', [
            'example' => $this->getExampleWithRelations($group, $method)
        ]);
    }

    private function getExampleWithRelations(Model $group, string $method)
    {
        return Example::query()
            ->with('requests', 'requests.queries', 'snippets', 'exception', 'queries', 'group')
            ->where([
                'group_id' => $group->id,
                'method_name' => $method,
            ])
            ->firstOrFail();
    }
}
