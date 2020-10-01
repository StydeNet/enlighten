<?php

namespace Tests;

use Illuminate\Config\Repository as Config;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Styde\Enlighten\EnlightenServiceProvider;

class TestCase extends OrchestraTestCase
{
    use TestHelpers;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function getPackageProviders($app)
    {
        return [
            EnlightenServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $this->configureDatabase($app['config']);
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
}
