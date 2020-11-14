<?php

namespace Tests\Unit;

use Styde\Enlighten\Providers\RegistersDatabaseConnection;
use Tests\TestCase;

class GuessDatabaseNameTest extends TestCase
{
    use RegistersDatabaseConnection;

    /** @test */
    function guesses_the_database_name()
    {
        $config = [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ];
        $this->assertSame(':memory:', $this->guessDatabaseName($config));

        // Add the _enlighten suffix
        $config = [
            'driver' => 'mysql',
            'database' => 'my_app',
        ];
        $this->assertSame('my_app_enlighten', $this->guessDatabaseName($config));

        // Remove the _tests suffix and add the _enlighten suffix
        $config = [
            'driver' => 'pqsql',
            'database' => 'my_app_tests',
        ];
        $this->assertSame('my_app_enlighten', $this->guessDatabaseName($config));

        // Remove the _test suffix and add the _enlighten suffix
        $config = [
            'driver' => 'mysql',
            'database' => 'my_app_test',
        ];
        $this->assertSame('my_app_enlighten', $this->guessDatabaseName($config));
    }
}
