<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class TestExampleGroupTest extends TestCase
{
    #[Test]
    public function it_saves_an_example_group_with_an_area_name(): void
    {
        $testExampleGroup = $this->createExampleGroup(null, 'Tests\Feature\ListUsersTest');
        $testExampleGroup->save();

        tap(ExampleGroup::first(), function ($exampleGroup) {
            $this->assertSame('feature', $exampleGroup->area);
        });
    }

    #[Test]
    public function the_area_name_can_be_determined_with_a_custom_resolver(): void
    {
        Settings::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });

        $testExampleGroup = $this->createExampleGroup(null, 'Modules\Field\Tests\Unit\Validations\FieldGroupValidationsTest');
        $testExampleGroup->save();

        tap(ExampleGroup::first(), function ($exampleGroup) {
            $this->assertSame('unit', $exampleGroup->area);
        });
    }
}
