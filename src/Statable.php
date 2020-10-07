<?php

namespace Styde\Enlighten;

interface Statable extends Statusable
{
    public function getPassingTestsCount(): int;

    public function getTestsCount(): int;
}
