<?php

namespace Tests;

use Illuminate\Config\Repository as Config;
use Illuminate\Contracts\Http\Kernel;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Styde\Enlighten\EnlightenServiceProvider;
use Styde\Enlighten\ExampleGenerator;
use Styde\Enlighten\ExampleGeneratorMiddleware;
use Tests\App\Providers\RouteServiceProvider;

class TestCase extends OrchestraTestCase
{
    use TestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/resources/views');

        $this->addCustomConfiguration();
    }

    protected function getPackageProviders($app)
    {
        return [
            RouteServiceProvider::class,
            EnlightenServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->configureDatabase($app['config']);

        $app[Kernel::class]->pushMiddleware(ExampleGeneratorMiddleware::class);
    }

    protected function configureDatabase(Config $config): void
    {
        // Setup default database to use sqlite :memory:
        $config->set('database.default', 'testbench');
        $config->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }

    protected function loadViewsFrom($dir): void
    {
        $this->app['view']->addLocation($dir);
    }

    protected function addCustomConfiguration(): void
    {
        $this->app->config->set([
            'enlighten.examples.directory' => __DIR__ . '/../examples/',
        ]);
    }
}
