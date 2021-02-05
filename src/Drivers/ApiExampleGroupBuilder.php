<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\Contracts\ExampleBuilder;

class ApiExampleGroupBuilder extends BaseExampleGroupBuilder
{
    /**
     * @var ApiRunBuilder
     */
    private $runBuilder;

    public function __construct(ApiRunBuilder $runBuilder)
    {
        $this->runBuilder = $runBuilder;
    }

    public function newExample(): ExampleBuilder
    {
        return new ApiExampleBuilder($this);
    }
}
