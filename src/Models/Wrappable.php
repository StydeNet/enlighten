<?php

namespace Styde\Enlighten\Models;

use Illuminate\Support\Str;

interface Wrappable
{
    // Helpers
    public function matches(Module $module): bool;
}
