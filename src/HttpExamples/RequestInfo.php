<?php

namespace Styde\Enlighten\HttpExamples;

class RequestInfo
{
    public function __construct(private readonly string $method, private readonly string $path, private readonly array $headers, private readonly array $queryParameters, private readonly array $input, private readonly array $files)
    {
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQueryParameters(): array
    {
        return $this->queryParameters;
    }

    public function getInput(): array
    {
        return $this->input;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
