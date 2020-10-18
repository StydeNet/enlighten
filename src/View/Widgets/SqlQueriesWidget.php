<?php

namespace Styde\Enlighten\View\Widgets;

use Illuminate\Contracts\Support\Responsable;
use Styde\Enlighten\Models\ExampleQuery;
use Symfony\Component\HttpFoundation\Response;

class SqlQueriesWidget implements Responsable
{
    public function toResponse($request)
    {
        $queries = ExampleQuery::where('example_id', $request->query('example'))->get();

        if ($queries->isEmpty()) {
            return response('', Response::HTTP_NO_CONTENT);
        }

        return view('enlighten::widgets.sql-queries', [
            'title' => 'Database Queries',
            'queries' => $queries
        ]);
    }
}
