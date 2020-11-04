<?php

namespace Tests\Feature;

use Styde\Enlighten\HttpExamples\HttpExampleCreatorMiddleware;

class TestCase extends \Tests\TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(HttpExampleCreatorMiddleware::class);
    }
}
