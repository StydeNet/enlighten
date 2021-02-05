<?php

namespace Styde\Enlighten;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ExampleProfile
{
    /**
     * @var array
     */
    protected $ignore;

    public function __construct(array $config)
    {
        $this->ignore = $config['ignore'];
    }

    public function shouldIgnore(string $className, string $methodName, ?array $options): bool
    {
        // If the test has been explicitly ignored via the
        // annotation options we need to ignore the test.
        if (Arr::get($options, 'ignore', false)) {
            return true;
        }

        // If the test has been explicitly included via the
        // annotation options we need to include the test.
        if (Arr::get($options, 'include', false)) {
            return false;
        }

        // Otherwise check the patterns we've got from the
        // config to check if the test should be ignored.
        if (Str::is($this->ignore, $className)) {
            return true;
        }

        return Str::is($this->ignore, $methodName);
    }
}
