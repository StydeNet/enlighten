<?php

namespace Styde\Enlighten\Utils;

use Illuminate\Support\Str;

class TestTrace
{
    public function get(): array
    {
        return collect(debug_backtrace())->first(function ($trace) {
            return Str::contains($trace['file'], '/phpunit/')
                && Str::endsWith($trace['file'], '/Framework/TestCase.php');
        });
    }
}
