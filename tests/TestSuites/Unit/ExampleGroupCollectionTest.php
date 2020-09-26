<?php

namespace Tests\Suites\Unit;

use Illuminate\Support\Collection;
use Styde\Enlighten\ExampleGroup;
use Tests\TestCase;

class ExampleGroupCollectionTest extends TestCase
{
    /** @test */
    function use_custom_example_group_collection()
    {
        $this->assertInstanceOf(Collection::class, ExampleGroup::all());
    }
}
