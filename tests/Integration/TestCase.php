<?php

namespace Tests\Integration;

use Styde\Enlighten\RecordsTestStatus;
use Tests\Integration\App\Providers\RouteServiceProvider;

class TestCase extends \Tests\TestCase
{
    use TestHelpers, RecordsTestStatus;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->loadViewsFrom(__DIR__ . '/resources/views');

        $this->recordTestStatus();
    }

    protected function getPackageProviders($app)
    {
        return array_merge(
            [RouteServiceProvider::class],
            parent::getPackageProviders($app),
        );
    }
}
