<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Support\Str;
use Styde\Enlighten\TestSuite;

class Controller
{
    protected function getTabs()
    {
        return TestSuite::all()->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => $value];
        });
    }
}