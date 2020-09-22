<?php

namespace Styde\Enlighten;

use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @property-read Example $title
 * @property-read Example $description
 * @property-read Example $request_headers
 * @property-read Example $request_method
 * @property-read Example $request_path
 * @property-read Example $request_query_parameters
 * @property-read Example $request_input
 * @property-read Example $route
 * @property-read Example $route_parameters
 * @property-read Example $response_headers
 * @property-read Example $response_status
 * @property-read Example $response_body
 * @property-read Example $response_template
 * @property-read Example $response_type
 * @property-read Example $full_path
 */
abstract class Example
{
    abstract public function getTitle(): string;

    abstract public function getDescription(): ?string;

    abstract public function getRequestHeaders(): array;

    abstract public function getRequestMethod(): string;

    abstract public function getRequestPath(): string;

    abstract public function getRequestQueryParameters(): array;

    abstract public function getRequestInput(): array;

    abstract public function getRoute(): string;

    abstract public function getRouteParameters(): array;

    abstract public function getResponseHeaders(): array;

    abstract public function getResponseStatus(): int;

    abstract public function getResponseBody();

    abstract public function getResponseTemplate(): ?string;

    public function getFullPath(): string
    {
        if (empty($this->getRequestQueryParameters())) {
            return $this->getRequestPath();
        }

        return $this->getRequestPath().'?'.http_build_query($this->getRequestQueryParameters());
    }

    public function getResponseType(): string
    {
        $contentTypes = [
            'text/html' => 'HTML',
            '/json' => 'JSON',
            'text/plain' => 'TEXT'
        ];

        return collect($contentTypes)->first(function ($label, $type) {
            return Str::contains($this->responseHeaders['content-type'][0], $type);
        });
    }

    /**
     * Dynamically call getters on the class using properties with snake case format.
     * i.e.: $example->full_path is equivalent to calling $example->getFullPath();
     *
     * @param string $attribute
     * @return mixed
     */
    public function __get($attribute)
    {
        $method = 'get'.Str::studly($attribute);

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new InvalidArgumentException("The method {$method} was not found in the CodeExample class");
    }
}
