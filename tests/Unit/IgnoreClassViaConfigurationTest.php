<?php

namespace Tests\TestSuites\Unit;

use Tests\TestCase;

class IgnoreClassViaConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // @TODO: make sure the configuration is reset after the test.
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
