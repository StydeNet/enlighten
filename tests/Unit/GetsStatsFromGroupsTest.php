<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\Concerns\GetsStatsFromGroups;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class GetsStatsFromGroupsTest extends TestCase
{
    #[Test]
    public function get_stats_from_an_example_group_collection(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Feature\FirstGroupTest');

        $this->createExample($group, 'first_test', 'passed');
        $this->createExample($group, 'second_test', 'passed');
        $this->createExample($group, 'third_test', 'passed');
        $this->createExample($group, 'fourth_test', 'passed');

        $parent = new class {
            use GetsStatsFromGroups;

            public $groups;
        };

        $parent->groups = ExampleGroup::with('stats')->get();

        $this->assertSame(4, $parent->getPassingTestsCount());
        $this->assertSame(4, $parent->getTestsCount());
        $this->assertSame('success', $parent->getStatus());

        $group2 = $this->createExampleGroup($run, 'Tests\Feature\SecondGroupTest');
        $this->createExample($group2, 'sixth_test', 'skipped');
        $parent->groups = ExampleGroup::with('stats')->get();

        $this->assertSame(4, $parent->getPassingTestsCount());
        $this->assertSame(5, $parent->getTestsCount());
        $this->assertSame('warning', $parent->getStatus());

        $this->createExample($group2, 'fifth_test', 'error');
        $parent->groups = ExampleGroup::with('stats')->get();

        $this->assertSame(4, $parent->getPassingTestsCount());
        $this->assertSame(6, $parent->getTestsCount());
        $this->assertSame('failure', $parent->getStatus());
    }
}
