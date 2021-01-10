<?php

namespace Styde\Enlighten;

interface RunBuilder
{
    public function newExampleGroup(): ExampleGroupBuilder;

    public function reset(): void;

    public function save();
}
