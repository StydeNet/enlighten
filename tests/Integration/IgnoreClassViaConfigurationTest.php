<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;

class IgnoreClassViaConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app->config->set([
            'enlighten.tests.ignore' => [
                '*IgnoreClass*',
            ],
        ]);
    }

    /** @test */
    function does_not_export_test_classes_ignored_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }
}
