<?php

namespace Styde\Enlighten;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ResponseInspector
{
    use ReplacesValues;

    public function getDataFrom(Response $response)
    {
        return new ResponseInfo(
            $response->getStatusCode(),
            $response->headers->all(),
            $response->getContent(),
            $this->getTemplate($response)
        );
    }

    protected function getTemplate(Response $response): ?string
    {
        if (isset($response->original) && $response->original instanceof View) {
            return File::get($response->original->getPath());
        }

        return null;
    }
}
