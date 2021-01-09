<?php

namespace Styde\Enlighten;

use Styde\Enlighten\Models\ExampleGroup;

interface ExampleGroupBuilder
{
    public function is(string $name): bool;

    public function save(): ExampleGroup;
}
