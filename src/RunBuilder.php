<?php

namespace Styde\Enlighten;

interface RunBuilder
{
    public function newExampleGroup(): ExampleGroupBuilder;
}
