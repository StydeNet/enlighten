<?php

namespace Styde\Enlighten\Contracts;

use Illuminate\Support\Collection;

interface Run
{
    public function isEmpty(): bool;

    public function getFailedExamples(): Collection;

    public function url(): string;
}
