<?php

namespace Styde\Enlighten\Contracts;

interface VersionControl
{
    public function currentBranch(): string;

    public function head(): string;

    public function modified(): bool;
}
