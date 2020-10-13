<?php

namespace Styde\Enlighten\Exceptions;

use BadMethodCallException;

class LaravelNotPresent extends BadMethodCallException
{
    public function __construct()
    {
        parent::__construct(
            "\n\n`Enlighten` requires Laravel to be present in your tests."
            ."\nPlease make sure the test class extend from Tests\TestCase or \Illuminate\Foundation\Testing\TestCase."
        );
    }
}
