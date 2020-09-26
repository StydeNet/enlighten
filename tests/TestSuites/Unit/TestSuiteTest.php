<?php

namespace Tests\Suites\Unit;

use Styde\Enlighten\ExampleGroup;
use Styde\Enlighten\TestSuite;
use Tests\TestCase;

class TestSuiteTest extends TestCase
{
    /** @test */
    function get_all_the_test_suites()
    {
        $this->createExampleGroup('Tests\Api\ListUsersTest');
        $this->createExampleGroup('Tests\Feature\CreateUserTest');
        $this->createExampleGroup('Tests\Feature\UpdateUserTest');
        $this->createExampleGroup('Tests\Unit\UserTest');

        $this->assertSame(['Api', 'Feature', 'Unit'], TestSuite::all()->values()->all());
    }

    public function createExampleGroup($className)
    {
        return ExampleGroup::create([
            'class_name' => $className,
            'title' => $className,
        ]);
    }
}
