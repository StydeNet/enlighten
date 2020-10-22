<?php

namespace Tests\Unit;

use Styde\Enlighten\Providers\EnlightenServiceProvider;
use Tests\TestCase;

class EnlightenServiceProviderTest extends TestCase
{
    /**
     * @var EnlightenServiceProvider
     */
    protected $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->provider = new EnlightenServiceProvider($this->app);
    }

    /** @test */
    function guesses_the_database_name()
    {
        $config = [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ];
        $this->assertSame(':memory:', $this->provider->guessDatabaseName($config));

        // Add the _enlighten suffix
        $config = [
            'driver' => 'mysql',
            'database' => 'my_app',
        ];
        $this->assertSame('my_app_enlighten', $this->provider->guessDatabaseName($config));

        // Remove the _tests suffix and add the _enlighten suffix
        $config = [
            'driver' => 'pqsql',
            'database' => 'my_app_tests',
        ];
        $this->assertSame('my_app_enlighten', $this->provider->guessDatabaseName($config));

        // Remove the _test suffix and add the _enlighten suffix
        $config = [
            'driver' => 'mysql',
            'database' => 'my_app_test',
        ];
        $this->assertSame('my_app_enlighten', $this->provider->guessDatabaseName($config));
    }
}
