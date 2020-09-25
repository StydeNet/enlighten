<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ResponseInspector
{
    private Collection $excludeHeaders;
    private Collection $overwriteHeaders;

    public function __construct(array $config)
    {
        $this->excludeHeaders = Collection::make($config['headers']['exclude'] ?? []);
        $this->overwriteHeaders = Collection::make($config['headers']['overwrite'] ?? []);
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
        return Collection::make($response->headers->all())
            ->exclude($this->excludeHeaders)
            ->overwrite($this->overwriteHeaders)
            ->all();
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
