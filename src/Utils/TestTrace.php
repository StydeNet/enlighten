<?php

namespace Styde\Enlighten\Utils;

use Illuminate\Support\Str;

class TestTrace
{
    public function get(): array
    {
        return collect(debug_backtrace())->first(function ($trace) {
            return Str::contains($trace['file'], DIRECTORY_SEPARATOR.'phpunit'.DIRECTORY_SEPARATOR)
                && Str::endsWith($trace['file'], DIRECTORY_SEPARATOR.'Framework'.DIRECTORY_SEPARATOR.'TestCase.php');
        });
    }
}
