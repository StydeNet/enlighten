<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ResponseInspector
{
    use ReplacesValues;

    private array $excludeHeaders;
    private array $overwriteHeaders;

    public function __construct(array $config)
    {
        $this->excludeHeaders = $config['headers']['exclude'] ?? [];
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
        return $this->replaceValues($response->headers->all(), $this->excludeHeaders, $this->overwriteHeaders);
    }

    // @TODO: revisit this.
    protected function getTemplate(Response $response): ?string
    {
        if (isset ($response->original) && $response->original instanceof View) {
            return var_export(File::get($response->original->getPath()), true);
        }

        return null;
    }
}
