<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class RunTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function gets_the_signature_of_a_run()
    {
        $run = $this->createRun('main', 'abcde', true);
        $this->assertSame('main * abcde', $run->signature);

        $run = $this->createRun('develop', 'fghij', false);
        $this->assertSame('develop fghij', $run->signature);
    }

    /** @test */
    function gets_the_url_of_the_run()
    {
        $run = $this->createRun();

        $this->assertSame('http://localhost/enlighten/run/1/areas', $run->url);
    }

    /** @test */
    function gets_the_base_url_of_the_run()
    {
        $run = $this->createRun();

        $this->assertSame('http://localhost/enlighten/run/1', $run->base_url);
    }

    /** @test */
    function gets_the_url_of_a_run_area()
    {
        $run = $this->createRun();

        $this->assertSame('http://localhost/enlighten/run/1/areas/feature', $run->areaUrl('feature'));
    }

    /** @test */
    function a_run_has_many_groups()
    {
        $run = $this->createRun();

        $this->assertInstanceOf(HasMany::class, $run->groups());
        $run->groups()->create($this->getExampleGroupAttributes());

        $this->assertInstanceOf(ExampleGroup::class, $run->groups->first());
    }

    /** @test */
    function gets_the_areas_of_a_run()
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest');
        $this->createExampleGroup($run, 'Tests\Api\CreateUserTest');

        // This configuration option allows the users to format or customise the name of the areas.
        $this->app->config->set('enlighten.areas', [
            'api' => 'API'
        ]);

        $expected = [
            [
                'title' => 'API',
                'slug' => 'api',
            ],
            [
                'title' => 'Feature',
                'slug' => 'feature',
            ],
        ];
        $this->assertSame($expected, $run->areas->toArray());
    }
}
