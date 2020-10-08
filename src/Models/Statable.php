<?php

namespace Styde\Enlighten\Models;

interface Statable extends Statusable
{
    public function getPassingTestsCount(): int;

    public function getTestsCount(): int;
}
