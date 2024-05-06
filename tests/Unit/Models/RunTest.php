<?php

namespace Tests\Unit\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use PHPUnit\Framework\Attributes\Test;
use Styde\Enlighten\Models\ExampleGroup;
use Tests\TestCase;

class RunTest extends TestCase
{
    #[Test]
    function gets_the_signature_of_a_run(): void
    {
        $run = $this->createRun('main', 'abcde', true);
        $this->assertSame('main * abcde', $run->signature);

        $run = $this->createRun('develop', 'fghij', false);
        $this->assertSame('develop fghij', $run->signature);
    }

    #[Test]
    function gets_the_url_of_the_run(): void
    {
        $run = $this->createRun();

        $this->assertSame('http://localhost/enlighten/run/1/areas', $run->url);
    }

    #[Test]
    function gets_the_base_url_of_the_run(): void
    {
        $run = $this->createRun();

        $this->assertSame('http://localhost/enlighten/run/1', $run->base_url);
    }

    #[Test]
    function gets_the_url_of_a_run_area(): void
    {
        $run = $this->createRun();

        $this->assertSame('http://localhost/enlighten/run/1/areas/feature', $run->areaUrl('feature'));
    }

    #[Test]
    function a_run_has_many_groups(): void
    {
        $run = $this->createRun();

        $this->assertInstanceOf(HasMany::class, $run->groups());
        $run->groups()->create($this->getExampleGroupAttributes());

        $this->assertInstanceOf(ExampleGroup::class, $run->groups->first());
    }

    #[Test]
    function gets_the_areas_of_a_run(): void
    {
        $run = $this->createRun();

        $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest');
        $this->createExampleGroup($run, 'Tests\Api\CreateUserTest');

        // This configuration option allows the users to format or customise the name of the areas.
        $this->app->config->set('enlighten.areas', [
            [
                'name' => 'API',
                'slug' => 'api',
            ]
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
        $this->assertSame($expected, $run->areas->toArray());
    }

    #[Test]
    public function run_has_many_examples(): void
    {
        $run = $this->createRun();
        $group = $this->createExampleGroup($run, 'Tests\Feature\ListUsersTest');
        $example = $this->createExample($group);

        $this->assertInstanceOf(HasManyThrough::class, $run->examples());
        $this->assertTrue($example->is($run->examples->first()));
    }
}
