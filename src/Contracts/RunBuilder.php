<?php

namespace Styde\Enlighten\Contracts;

interface RunBuilder
{
    public function newExampleGroup(): ExampleGroupBuilder;

    public function reset(): void;

    public function save(): Run;

    public function getRun(): Run;
}
