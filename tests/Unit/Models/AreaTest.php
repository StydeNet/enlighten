<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Facades\Settings;
use Styde\Enlighten\Models\Area;
use Tests\TestCase;

class AreaTest extends TestCase
{
    #[Test]
    function get_all_the_areas_from_the_current_run(): void
    {
        config([
            'enlighten.areas' => []
        ]);

        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Api\ApiRequestTest');
        $this->createExampleGroup($run, 'Tests\Feature\CreateUserTest');
        $this->createExampleGroup($run, 'Tests\Feature\UpdateUserTest');
        $this->createExampleGroup($run, 'Tests\Unit\UserTest');

        $expected = [
            [
                'name' => 'Api',
                'slug' => 'api',
                'view' => 'features',
            ],
            [
                'name' => 'Feature',
                'slug' => 'feature',
                'view' => 'features',
            ],
            [
                'name' => 'Unit',
                'slug' => 'unit',
                'view' => 'features',
            ],
        ];

        $this->assertSame($expected, $run->areas->toArray());
    }

    #[Test]
    function get_all_the_areas_from_the_current_groups_with_a_custom_area_resolver(): void
    {
        $this->setConfig([
            'enlighten.areas' => []
        ]);

        Settings::setCustomAreaResolver(function ($className) {
            return explode('\\', $className)[3];
        });

        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Modules\User\Api\ApiRequestTest');
        $this->createExampleGroup($run, 'Tests\Modules\User\Feature\CreateUserTest');

        $expected = [
            [
                'name' => 'Api',
                'slug' => 'api',
                'view' => 'features',
            ],
            [
                'name' => 'Feature',
                'slug' => 'feature',
                'view' => 'features',
            ],
        ];

        $this->assertArrayable($expected, $run->areas);
    }

    #[Test]
    function gets_all_the_areas_from_the_configuration(): void
    {
        $this->setConfig([
            'enlighten.areas' => [
                [
                    'name' => 'API',
                    'slug' => 'api',
                ],
                [
                    'slug' => 'feature',
                ],
            ],
        ]);

        $expected = [
            [
                'name' => 'API',
                'slug' => 'api',
                'view' => 'features',
            ],
            [
                'name' => 'Feature',
                'slug' => 'feature',
                'view' => 'features',
            ],
        ];
        $this->assertSame($expected, Area::all()->values()->toArray());
    }
}
