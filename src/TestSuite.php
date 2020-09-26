<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\DB;

class TestSuite
{
    public static function all()
    {
        if (config()->has('enlighten.test-suites')) {
            return collect(config('enlighten.test-suites'));
        }

        return DB::connection('enlighten')
            ->table('enlighten_example_groups')
            ->pluck('class_name')
            ->mapWithKeys(function ($classNames) {
                $name = explode('\\', $classNames)[1];

                return [$name => $name];
            })
            ->unique();
    }
}
