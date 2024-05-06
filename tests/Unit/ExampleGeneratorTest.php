<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\HttpExamples\HttpExampleCreator;
use Tests\TestCase;

class ExampleGeneratorTest extends TestCase
{
    #[Test]
    function the_example_generator_is_registered_as_singleton(): void
    {
        $this->assertSame(app(HttpExampleCreator::class), app(HttpExampleCreator::class));
    }
}
