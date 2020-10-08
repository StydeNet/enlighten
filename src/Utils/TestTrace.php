<?php

namespace Styde\Enlighten\Utils;

use Illuminate\Support\Str;
use ReflectionMethod;

class TestTrace
{
    public function get(): array
    {
        $trace = collect(debug_backtrace())->first(function ($trace) {
            return Str::contains($trace['file'], '/phpunit/')
                && Str::endsWith($trace['file'], '/Framework/TestCase.php');
        });

        $reflection = new ReflectionMethod($trace['class'], $trace['function']);

        $trace['start_line'] = $reflection->getStartLine();

        return $trace;
    }
}
