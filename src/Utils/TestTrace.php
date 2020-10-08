<?php

namespace Styde\Enlighten\Utils;

use Illuminate\Support\Str;

class TestTrace
{
    public string $className;
    public string $methodName;

    public static function get(): self
    {
        return new static(collect(debug_backtrace())->first(function ($trace) {
            return Str::contains($trace['file'], '/phpunit/')
                && Str::endsWith($trace['file'], '/Framework/TestCase.php');
        }));
    }

    public function __construct(array $trace)
    {
        $this->className = $trace['class'];
        $this->methodName = $trace['function'];
        $this->line = $trace['line'];
    }
}
