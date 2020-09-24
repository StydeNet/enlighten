<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ResponseInspector
{
    public function getInfoFrom(Response $response)
    {
        return new ResponseInfo(
            $response->getStatusCode(),
            $this->getHeaders($response),
            $this->getContent($response),
            $this->getTemplate($response)
        );
    }

    // @TODO: allow users to allow or blocklist the response headers.
    protected function getHeaders(Response $response): array
    {
        return $response->headers->all();
    }

    protected function getContent(Response $response): string
    {
        return $response->getContent();
    }

    // @TODO: revisit this.
    protected function getTemplate(Response $response): ?string
    {
        if ($response->original instanceof View) {
            return var_export(File::get($response->original->getPath()), true);
        }

        return null;
    }
}
