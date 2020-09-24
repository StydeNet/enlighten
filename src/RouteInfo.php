<?php

namespace Styde\Enlighten;

class RouteInfo
{
    private string $uri;
    private array $parameters;

    public function __construct(string $uri, array $parameters = [])
    {
        $this->uri = $uri;
        $this->parameters = $parameters;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
