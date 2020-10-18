<?php

namespace Styde\Enlighten;

class CodeSnippet
{
    public string $code;
    public $result;
    public array $params;
    public array $args;

    public function __construct(string $code, $result, array $params = [], array $args = [])
    {
        $this->code = $code;
        $this->result = $result;
        $this->params = $params;
        $this->args = $args;
    }
}
