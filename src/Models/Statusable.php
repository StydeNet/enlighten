<?php

namespace Styde\Enlighten\Models;

interface Statusable
{
    public function getStatus(): string;
}
