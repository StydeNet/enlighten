<?php

namespace Styde\Enlighten\Console;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class ContentRequest
{
    /**
     * @var HttpKernel
     */
    private $httpKernel;

    public function __construct(HttpKernel $httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    public function getContent(string $url)
    {
        $symfonyRequest = SymfonyRequest::create($url, 'GET');

        $response = $this->httpKernel->handle(Request::createFromBase($symfonyRequest));

        return $response->getContent();
    }
}
