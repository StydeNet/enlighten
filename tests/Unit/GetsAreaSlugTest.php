<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Settings;
use Tests\TestCase;

class GetsAreaSlugTest extends TestCase
{
    /** @test */
    function gets_the_slug_of_an_area_from_a_class_name()
    {
        $this->assertSame('feature', Settings::getAreaSlug('Tests\Feature\ListUsers'));
        $this->assertSame('api', Settings::getAreaSlug('Tests\Api\CreateUser'));
    }

    /** @test */
    function can_resolve_the_area_with_a_custom_resolver()
    {
        Settings::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });

        $this->assertSame('feature', Settings::getAreaSlug('Modules\Field\Tests\Feature\FieldGroupTest'));
        $this->assertSame('unit', Settings::getAreaSlug('Modules\Field\Tests\Unit\Validations\FieldGroupValidationsTest'));
    }
}
