<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\ExampleGroupBuilder;
use Styde\Enlighten\Contracts\Run;
use Styde\Enlighten\Contracts\RunBuilder;

class ApiRunBuilder implements RunBuilder
{
    public function newExampleGroup(): ExampleGroupBuilder
    {
        return new ApiExampleGroupBuilder($this);
    }

    public function reset(): void
    {
        // TODO: Implement reset() method.
    }

    public function save(): Run
    {
        // TODO: Implement save() method.
    }

    public function getRun(): Run
    {
        // TODO: Implement getRun() method.
    }
}
