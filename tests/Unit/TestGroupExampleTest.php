<?php

namespace Tests\Unit;

use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\TestExampleGroup;
use Tests\TestCase;

class TestGroupExampleTest extends TestCase
{
    /** @test */
    function it_gets_a_default_title()
    {
        $testExampleGroup = new TestExampleGroup('ListUsersTest');
        $this->assertSame('List Users', $testExampleGroup->getTitle());

        $testExampleGroup = new TestExampleGroup('ListTestsTest');
        $this->assertSame('List Tests', $testExampleGroup->getTitle());

        $testExampleGroup = new TestExampleGroup('ShowUsers');
        $this->assertSame('Show Users', $testExampleGroup->getTitle());

        $testExampleGroup = new TestExampleGroup('CreateTestTest');
        $this->assertSame('Create Test', $testExampleGroup->getTitle());
    }

    /** @test */
    public function it_saves_an_example_group_with_a_suite_name(): void
    {
        $testExampleGroup = new TestExampleGroup('Tests\Feature\ListUsersTest');
        $testExampleGroup->save();

        tap(ExampleGroup::first(), function ($exampleGroup) {
            $this->assertSame('feature', $exampleGroup->area);
        });
    }
}
