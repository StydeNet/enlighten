<?php

namespace Styde\Enlighten\HttpExamples;

class ResponseInfo
{
    /**
     * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string|null
     */
    private $template;

    /**
     * @var int
     */
    private $statusCode;

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
