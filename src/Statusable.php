<?php

namespace Styde\Enlighten;

interface Statusable
{
    public function getStatus();

    public function hasPassed();

    public function hasFailed();
}
