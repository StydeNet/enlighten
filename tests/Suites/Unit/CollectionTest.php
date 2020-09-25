<?php

namespace Tests\Suites\Unit;

use Styde\Enlighten\Collection;
use Tests\TestCase;

class CollectionTest extends TestCase
{
    /** @test */
    function get_items_that_match_a_pattern()
    {
        $collection = Collection::make([
            ['class_name' => 'ListUsersTest'],
            ['class_name' => 'UpdatePostsTest'],
            ['class_name' => 'CreateProjectsTest'],
            ['class_name' => 'SearchUsersTest'],
            ['class_name' => 'CreateUserTest'],
        ]);

        $expected = [
            ['class_name' => 'ListUsersTest'],
            ['class_name' => 'SearchUsersTest'],
            ['class_name' => 'CreateUserTest'],
        ];
        $this->assertSame($expected, $collection->match('class_name', ['*Users*', '*User*'])->values()->all());
    }
}
