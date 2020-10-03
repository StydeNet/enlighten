<?php

namespace Tests\Unit;

use Styde\Enlighten\HttpExampleGenerator;
use Tests\TestCase;

class ExampleGeneratorTest extends TestCase
{
    /** @test */
    function the_example_generator_is_registered_as_singleton()
    {
        $this->assertSame(app(HttpExampleGenerator::class), app(HttpExampleGenerator::class));
    }
}
