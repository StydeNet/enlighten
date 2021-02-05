<?php

namespace Tests\Integration;

use Styde\Enlighten\Enlighten;
use Styde\Enlighten\Tests\EnlightenSetup;
use Tests\Integration\App\Providers\RouteServiceProvider;

class TestCase extends \Tests\TestCase
{
    use TestHelpers, EnlightenSetup;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/Database/migrations');

        $this->loadViewsFrom(__DIR__ . '/resources/views');

        Enlighten::document();

        $this->setUpEnlighten();
    }

    protected function tearDown(): void
    {
        Enlighten::stopDocumenting();
    }

    protected function getPackageProviders($app)
    {
        return array_merge(
            [RouteServiceProvider::class],
            parent::getPackageProviders($app),
        );
    }
}
