<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ResponseInspector
{
    use ReplacesValues;

    private array $ignoreHeaders;
    private array $overwriteHeaders;

    public function __construct(array $config)
    {
        $this->ignoreHeaders = $config['headers']['ignore'] ?? [];
        $this->overwriteHeaders = $config['headers']['overwrite'] ?? [];
    }

    public function getInfoFrom(Response $response)
    {
        return new ResponseInfo(
            $response->getStatusCode(),
            $this->getHeaders($response),
            $response->getContent(),
            $this->getTemplate($response)
        );
    }

    protected function getHeaders(Response $response): array
    {
        return $this->replaceValues($response->headers->all(), $this->ignoreHeaders, $this->overwriteHeaders);
    }

    protected function getTemplate(Response $response): ?string
    {
        if (isset ($response->original) && $response->original instanceof View) {
            return File::get($response->original->getPath());
        }

        return null;
    }
}
