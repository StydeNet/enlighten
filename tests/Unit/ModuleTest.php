<?php

namespace Tests\Unit;

use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Module;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    /** @test */
    public function get_stats_of_module(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run);

        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'first_test', 'test_status' => 'passed']);
        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'second_test', 'test_status' => 'passed']);
        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'third_test', 'test_status' => 'passed']);
        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'fourth_test', 'test_status' => 'passed']);

        $module = new Module('All', ['*']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(4, $module->getTestsCount());
        $this->assertSame('passed', $module->getStatus());

        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'sixth_test', 'test_status' => 'skipped']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(5, $module->getTestsCount());
        $this->assertSame('warned', $module->getStatus());


        $this->createExampleTest(['group_id' => $group->id, 'method_name' => 'fifth_test', 'test_status' => 'error']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->getPassingTestsCount());
        $this->assertSame(6, $module->getTestsCount());
        $this->assertSame('failed', $module->getStatus());
    }
}
