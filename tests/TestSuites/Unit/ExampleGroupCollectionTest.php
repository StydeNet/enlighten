<?php

namespace Tests\Suites\Unit;

use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroup;
use Styde\Enlighten\ExampleGroupCollection;
use Tests\TestCase;

class ExampleGroupCollectionTest extends TestCase
{
    /** @test */
    function use_custom_example_group_collection()
    {
        $this->assertInstanceOf(ExampleGroupCollection::class, ExampleGroup::all());
    }

    /** @test */
    public function some_test(): void
    {
        $collection = ExampleGroupCollection::make([
            new Example(['class_name' => 'Tests\Api\ListUsersTest']),
            new Example(['class_name' => 'Tests\Feature\UpdatePostsTest']),
            new Example(['class_name' => 'Tests\Unit\CreateProjectsTest']),
            new Example(['class_name' => 'Tests\Api\User\SearchUsersTest']),
            new Example(['class_name' => 'Tests\Feature\CreateUserTest']),
        ]);

        $this->assertSame(['Api', 'Feature', 'Unit'], $collection->getTestSuites());
    }
}
