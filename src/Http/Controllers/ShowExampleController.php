<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\Run;

class ShowExampleController
{
    public function __invoke(Run $run, string $groupSlug, string $exampleSlug)
    {
        $group = $run->findGroup($groupSlug);

        return view('enlighten::example.show', [
            'example' => $this->getExampleWithRelations($group, $exampleSlug)
        ]);
    }

    private function getExampleWithRelations(Model $group, string $exampleSlug)
    {
        return Example::query()
            ->with('requests', 'requests.queries', 'snippets', 'exception', 'queries')
            ->where([
                'group_id' => $group->id,
                'slug' => $exampleSlug,
            ])
            ->firstOrFail()
            ->setRelation('group', $group);
    }
}
