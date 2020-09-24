<?php

namespace Styde\Enlighten;

class ResponseInfo
{
    private array $headers;
    private string $content;
    private ?string $template;
    private int $statusCode;

    public function __construct(int $statusCode, array $headers, string $content, ?string $template)
    {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->content = $content;
        $this->template = $template;
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
