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

        $expected = [
            'Api' => 'Api',
            'Feature' => 'Feature',
            'Unit' => 'Unit'
        ];
        $this->assertSame($expected, TestSuite::all()->toArray());
    }

    /** @test */
    function get_test_suites_from_config()
    {
        $this->setConfig([
            'enlighten.test-suites' => [
                'Api' => 'API',
                'Feature' => 'Features',
            ],
        ]);

        $expected = [
            'Api' => 'API',
            'Feature' => 'Features',
        ];
        $this->assertSame($expected, TestSuite::all()->toArray());
    }

    protected function createExampleGroup($className)
    {
        return ExampleGroup::create([
            'class_name' => $className,
            'title' => $className,
        ]);
    }
}
