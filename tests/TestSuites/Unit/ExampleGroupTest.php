<?php

namespace Tests\Suites\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Example;
use Styde\Enlighten\ExampleGroup;
use Tests\TestCase;

class ExampleGroupTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function get_example_groups_by_test_suite(): void
    {
        ExampleGroup::create(['class_name' => 'Tests\Api\UserTest', 'title' => 'User tests']);
        ExampleGroup::create(['class_name' => 'Tests\Api\PostTest', 'title' => 'Post tests']);
        ExampleGroup::create(['class_name' => 'Tests\Feature\UserTest', 'title' => 'Users Feature tests']);
        ExampleGroup::create(['class_name' => 'Tests\Unit\FilterTest', 'title' => 'Filter tests']);

        $tests = ExampleGroup::findByTestSuite('api');

        $this->assertSame(
            [
                'Tests\Api\UserTest',
                'Tests\Api\PostTest'
            ],
            $tests->pluck('class_name')->all()
        );
    }

}