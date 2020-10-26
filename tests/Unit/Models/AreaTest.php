<?php

namespace Tests\Unit\Models;

use Styde\Enlighten\Facades\Enlighten;
use Styde\Enlighten\Models\Area;
use Tests\TestCase;

class AreaTest extends TestCase
{
    /** @test */
    function get_all_the_areas_from_the_current_groups()
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\ApiRequestTest');
        $this->createExampleGroup($run, 'Tests\Feature\CreateUserTest');
        $this->createExampleGroup($run, 'Tests\Feature\UpdateUserTest');
        $this->createExampleGroup($run, 'Tests\Unit\UserTest');

        $expected = [
            [
                'key' => 'api',
                'title' => 'Api',
                'slug' => 'api',
            ],
            [
                'key' => 'feature',
                'title' => 'Feature',
                'slug' => 'feature',
            ],
            [
                'key' => 'unit',
                'title' => 'Unit',
                'slug' => 'unit',
            ],
        ];

        $this->assertSame($expected, Area::all()->values()->toArray());
    }

    /** @test */
    function get_all_the_areas_from_the_current_groups_with_a_custom_area_resolver()
    {
        Enlighten::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });

        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Modules\User\Api\ApiRequestTest');
        $this->createExampleGroup($run, 'Tests\Modules\User\Feature\CreateUserTest');

        $expected = [
            [
                'key' => 'api',
                'title' => 'Api',
                'slug' => 'api',
            ],
            [
                'key' => 'feature',
                'title' => 'Feature',
                'slug' => 'feature',
            ],
        ];

        $this->assertSame($expected, Area::all()->values()->toArray());
    }

    /** @test */
    function gets_all_the_areas_from_the_configuration_as_a_simple_array()
    {
        $this->setConfig([
            'enlighten.areas' => ['Feature', 'Unit'],
        ]);

        $expected = [
            [
                'key' => 'Feature',
                'title' => 'Feature',
                'slug' => 'feature',
            ],
            [
                'key' => 'Unit',
                'title' => 'Unit',
                'slug' => 'unit',
            ],
        ];
        $this->assertSame($expected, Area::all()->values()->toArray());
    }

    /** @test */
    function gets_all_the_areas_from_the_configuration_as_an_associative_array()
    {
        $this->setConfig([
            'enlighten.areas' => [
                'Api' => 'API',
                'Feature' => 'Features',
            ],
        ]);

        $expected = [
            [
                'key' => 'Api',
                'title' => 'API',
                'slug' => 'api',
            ],
            [
                'key' => 'Feature',
                'title' => 'Features',
                'slug' => 'feature',
            ],
        ];
        $this->assertSame($expected, Area::all()->values()->toArray());
    }
}
