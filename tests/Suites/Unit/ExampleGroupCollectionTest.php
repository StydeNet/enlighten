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
    function get_items_that_match_a_pattern()
    {
        $collection = ExampleGroupCollection::make([
            new Example(['class_name' => 'ListUsersTest']),
            new Example(['class_name' => 'UpdatePostsTest']),
            new Example(['class_name' => 'CreateProjectsTest']),
            new Example(['class_name' => 'SearchUsersTest']),
            new Example(['class_name' => 'CreateUserTest']),
        ]);

        $expected = [
            ['class_name' => 'ListUsersTest'],
            ['class_name' => 'SearchUsersTest'],
            ['class_name' => 'CreateUserTest'],
        ];
        $this->assertSame($expected, $collection->match('class_name', ['*Users*', '*User*'])->values()->toArray());
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
