<?php

namespace Styde\Enlighten;

class CodeSnippet
{
    public string $code;
    public $result;
    public array $params;

    public function __construct(string $code, $result, array $params = [])
    {
        $this->code = $code;
        $this->result = $result;
        $this->params = $params;
    }
}
