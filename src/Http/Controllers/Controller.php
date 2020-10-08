<?php

namespace Styde\Enlighten\Http\Controllers;

use Illuminate\Support\Str;
use Styde\Enlighten\Models\Area;

class Controller
{
    protected function getTabs()
    {
        return Area::all()->mapWithKeys(function ($value, $key) {
            return [Str::slug($key) => $value];
        });
    }
}
