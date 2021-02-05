<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\Example;

class ApiExample implements Example
{
    public function getSignature(): string
    {
        return 'implement me!';
    }

    public function getStatus(): string
    {
        return 'implement me!';
    }

    public function getUrl(): string
    {
        return 'implement me!';
    }
}
