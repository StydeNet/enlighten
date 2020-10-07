<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\GetsStatsFromGroups;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\ReadsDynamicAttributes;
use Tests\TestCase;

class GetsStatsFromGroupsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function get_the_stats_of_a_module(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'FirstGroupTest');

        $this->createExample($group, 'first_test', 'passed');
        $this->createExample($group, 'second_test', 'passed');
        $this->createExample($group, 'third_test', 'passed');
        $this->createExample($group, 'fourth_test', 'passed');

        $module = new class {
            use GetsStatsFromGroups;

            public $groups;
        };

        $module->groups = ExampleGroup::with('stats')->get();

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(4, $module->getTestsCount());
        $this->assertSame('passed', $module->getStatus());

        $group2 = $this->createExampleGroup($run, 'SecondGroupTest');
        $this->createExample($group2, 'sixth_test', 'skipped');
        $module->groups = ExampleGroup::with('stats')->get();

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(5, $module->getTestsCount());
        $this->assertSame('warned', $module->getStatus());

        $this->createExample($group2, 'fifth_test', 'error');
        $module->groups = ExampleGroup::with('stats')->get();

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(6, $module->getTestsCount());
        $this->assertSame('failed', $module->getStatus());
    }
}
