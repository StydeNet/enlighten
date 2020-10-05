<?php

namespace Tests\Unit\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\Example;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\TestSuite;
use Tests\TestCase;

class ExampleGroupTest extends TestCase {

    use RefreshDatabase;

    /** @test */
    public function get_example_groups_by_test_suite(): void
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\UserTest');
        $this->createExampleGroup($run, 'Tests\Api\PostTest');
        $this->createExampleGroup($run, 'Tests\Feature\UserTest');
        $this->createExampleGroup($run, 'Tests\Unit\FilterTest');

        $tests = ExampleGroup::findByTestSuite(new TestSuite('Api'));

        $this->assertSame(
            ['Tests\Api\UserTest', 'Tests\Api\PostTest'],
            $tests->pluck('class_name')->all()
        );
    }

    /** @test */
    public function gets_the_test_stats_of_a_group(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Api\UserTest');

        $this->createExampleTest(['group_id' => $group->id, 'test_status' => 'passed']);

        $group = ExampleGroup::with('stats')->first();

        $this->assertSame(['passed' => 1], $group->stats->pluck('count', 'test_status')->toArray());

        $this->assertSame(1, $group->passing_tests_count);
    }
}
