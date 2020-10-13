<?php

namespace Styde\Enlighten\Exceptions;

use BadMethodCallException;

class LaravelNotPresent extends BadMethodCallException
{
    public function __construct()
    {
        parent::__construct(
            "\n\nLaravel needs to be present to `Enlighten` your tests."
            ."\nPlease make this class extend from Tests\TestCase or \Illuminate\Foundation\Testing\TestCase."
        );
    }
}
