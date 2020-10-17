<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\ExampleGroup;
use Styde\Enlighten\TestExampleGroup;
use Tests\TestCase;

class TestExampleGroupTest extends TestCase
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
    public function it_saves_an_example_group_with_an_area_name(): void
    {
        $testExampleGroup = new TestExampleGroup('Tests\Feature\ListUsersTest');
        $testExampleGroup->save();

        tap(ExampleGroup::first(), function ($exampleGroup) {
            $this->assertSame('feature', $exampleGroup->area);
        });
    }

    /** @test */
    public function the_area_name_can_be_determined_with_a_custom_resolver(): void
    {
        Enlighten::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });

        $testExampleGroup = new TestExampleGroup('Modules\Field\Tests\Unit\Validations\FieldGroupValidationsTest');
        $testExampleGroup->save();

        tap(ExampleGroup::first(), function ($exampleGroup) {
            $this->assertSame('unit', $exampleGroup->area);
        });
    }
}
