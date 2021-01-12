<?php

namespace Styde\Enlighten\Drivers;

use Styde\Enlighten\DatabaseRunBuilder;
use Styde\Enlighten\ExampleBuilder;
use Styde\Enlighten\ExampleGroupBuilder;

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
