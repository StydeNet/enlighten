<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Area;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class ExampleGroupTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_example_groups_by_test_area(): void
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest');
        $this->createExampleGroup($run, 'Tests\Api\PostTest');
        $this->createExampleGroup($run, 'Tests\Feature\UserTest');
        $this->createExampleGroup($run, 'Tests\Unit\FilterTest');

        $tests = ExampleGroup::filterByArea(new Area('api'))->get();

        $this->assertSame(
            ['Tests\Api\UserTest', 'Tests\Api\PostTest'],
            $tests->pluck('class_name')->all()
        );
    }

    /** @test */
    public function get_the_stats_of_an_example_group(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Feature\FirstGroupTest');

        $this->createExample($group, 'first_test', 'passed');
        $this->createExample($group, 'second_test', 'passed');
        $this->createExample($group, 'third_test', 'passed');
        $this->createExample($group, 'fourth_test', 'passed');

        $this->assertSame(4, $group->passing_tests_count);
        $this->assertSame(4, $group->tests_count);
        $this->assertSame('success', $group->status);

        $this->createExample($group, 'sixth_test', 'skipped');
        $group->load('stats');

        $this->assertSame(4, $group->passing_tests_count);
        $this->assertSame(5, $group->tests_count);
        $this->assertSame('warning', $group->status);

        $this->createExample($group, 'fifth_test', 'error');
        $group->load('stats');

        $this->assertSame(4, $group->passing_tests_count);
        $this->assertSame(6, $group->tests_count);
        $this->assertSame('failure', $group->status);
    }

    /** @test */
    function get_the_example_group_url()
    {
        $exampleGroup = new ExampleGroup([
            'run_id' => 1,
            'area' => 'feature',
            'slug' => 'api-request',
        ]);

        $this->assertSame('http://localhost/enlighten/run/1/feature/api-request', $exampleGroup->url);

        $exampleGroup = new ExampleGroup([
            'run_id' => 2,
            'area' => 'feature',
            'slug' => 'list-users'
        ]);

        $this->assertSame('http://localhost/enlighten/run/2/feature/list-users', $exampleGroup->url);
    }
}
