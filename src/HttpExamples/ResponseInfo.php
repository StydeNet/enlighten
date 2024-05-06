<?php

namespace Styde\Enlighten\HttpExamples;

class ResponseInfo
{
    public function __construct(private readonly int $statusCode, private readonly array $headers, private readonly string $content, private readonly ?string $template)
    {
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }
}
