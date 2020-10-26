<?php

namespace Styde\Enlighten\Http\Controllers;

use Styde\Enlighten\Models\Area;

class Controller
{
    protected function getTabs()
    {
        return Area::all();
    }
}
