<?php

namespace Styde\Enlighten;

class CodeSnippet
{
    public string $code;
    public array $params;

    public function __construct(string $code, array $params = [])
    {
        $this->code = $code;
        $this->params = $params;
    }
}
