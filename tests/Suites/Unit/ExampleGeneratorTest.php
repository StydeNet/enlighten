<?php

namespace Tests\Suites\Unit;

use Styde\Enlighten\ExampleGenerator;
use Tests\TestCase;

class ExampleGeneratorTest extends TestCase
{
    /** @test */
    function the_example_generator_is_registered_as_singleton()
    {
        $this->assertSame(app(ExampleGenerator::class), app(ExampleGenerator::class));
    }
}
