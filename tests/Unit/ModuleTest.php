<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\Module;
use Tests\TestCase;

class ModuleTest extends TestCase
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

        $module = new Module('All', ['*']);
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->passing_tests_count);
        $this->assertSame(4, $module->tests_count);
        $this->assertSame('passed', $module->status);

        $group2 = $this->createExampleGroup($run, 'SecondGroupTest');
        $this->createExample($group2, 'sixth_test', 'skipped');
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->passing_tests_count);
        $this->assertSame(5, $module->tests_count);
        $this->assertSame('warned', $module->status);

        $this->createExample($group2, 'fifth_test', 'error');
        $module->addGroups(ExampleGroup::with('stats')->get());

        $this->assertSame(4, $module->passing_tests_count);
        $this->assertSame(6, $module->tests_count);
        $this->assertSame('failed', $module->status);
    }
}
