<?php

namespace Styde\Enlighten;

interface Statusable
{
    public function getStatus(): string;

    public function hasPassed(): bool;

    public function hasFailed(): bool;
}
