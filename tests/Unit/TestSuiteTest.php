<?php

namespace Tests\Unit;

use Styde\Enlighten\ExampleGroup;
use Styde\Enlighten\TestSuite;
use Tests\TestCase;

class TestSuiteTest extends TestCase
{
    /** @test */
    function get_all_the_test_suites()
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\ApiRequestTest');
        $this->createExampleGroup($run, 'Tests\Feature\CreateUserTest');
        $this->createExampleGroup($run, 'Tests\Feature\UpdateUserTest');
        $this->createExampleGroup($run, 'Tests\Unit\UserTest');

        $expected = [
            [
                'key' => 'Api',
                'title' => 'Api',
                'slug' => 'api',
            ],
            [
                'key' => 'Feature',
                'title' => 'Feature',
                'slug' => 'feature',
            ],
            [
                'key' => 'Unit',
                'title' => 'Unit',
                'slug' => 'unit',
            ],
        ];

        $this->assertSame($expected, TestSuite::all()->values()->toArray());
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
            [
                'key' => 'Api',
                'title' => 'API',
                'slug' => 'api',
            ],
            [
                'key' => 'Feature',
                'title' => 'Features',
                'slug' => 'feature',
            ],
        ];
        $this->assertSame($expected, TestSuite::all()->values()->toArray());
    }
}
