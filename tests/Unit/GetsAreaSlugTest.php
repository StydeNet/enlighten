<?php

namespace Tests\Unit;

use Styde\Enlighten\Facades\Enlighten;
use Tests\TestCase;

class GetsAreaSlugTest extends TestCase
{
    /** @test */
    function gets_the_slug_of_an_area_from_a_class_name()
    {
        $this->assertSame('feature', Enlighten::getAreaSlug('Tests\Feature\ListUsers'));
        $this->assertSame('api', Enlighten::getAreaSlug('Tests\Api\CreateUser'));
    }

    /** @test */
    function can_resolve_the_area_with_a_custom_resolver()
    {
        Enlighten::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });

        $this->assertSame('feature', Enlighten::getAreaSlug('Modules\Field\Tests\Feature\FieldGroupTest'));
        $this->assertSame('unit', Enlighten::getAreaSlug('Modules\Field\Tests\Unit\Validations\FieldGroupValidationsTest'));
    }
}
