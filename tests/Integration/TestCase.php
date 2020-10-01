<?php

namespace Tests\Integration;

use Tests\Integration\App\Providers\RouteServiceProvider;

class TestCase extends \Tests\TestCase
{
    use TestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->loadViewsFrom(__DIR__ . '/resources/views');
    }

    protected function getPackageProviders($app)
    {
        return array_merge(
            [RouteServiceProvider::class],
            parent::getPackageProviders($app),
        );
    }
}
