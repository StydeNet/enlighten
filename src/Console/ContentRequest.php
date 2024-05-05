<?php

namespace Styde\Enlighten\Console;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ContentRequest
{

    public function __construct(private readonly HttpKernel $httpKernel)
    {
    }

    public function getContent(string $url): false|string
    {
        $symfonyRequest = SymfonyRequest::create($url, 'GET');

        $response = $this->httpKernel->handle(Request::createFromBase($symfonyRequest));

        return $response->getContent();
    }
}
