<?php

namespace Styde\Enlighten\Contracts;

interface Example
{
    public function getSignature(): string;

    public function getTitle(): string;

    public function getStatus(): string;

    public function getUrl(): string;
}
