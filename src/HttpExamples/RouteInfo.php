<?php

namespace Styde\Enlighten\HttpExamples;

class RouteInfo
{
    /**
     * @var string|null
     */
    private $uri;

    /**
     * @var array|null
     */
    private $parameters;

    public function __construct(?string $uri, array $parameters = [])
    {
        if (is_null($uri)) {
            $this->uri = null;
            $this->parameters = null;
        } else {
            $this->uri = $uri;
            $this->parameters = $parameters;
        }
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }
}
