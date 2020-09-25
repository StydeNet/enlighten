<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\DB;

class TestSuite
{
    public static function all()
    {
        return DB::connection('enlighten')->table('enlighten_example_groups')
            ->pluck('class_name')
            ->map(function ($classNames) {
                return explode('\\', $classNames)[1];
            })
            ->unique();
    }
}
