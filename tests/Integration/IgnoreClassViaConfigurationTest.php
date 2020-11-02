<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;

class IgnoreClassViaConfigurationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->setConfig([
                'enlighten.tests.ignore' => [
                    '*IgnoreClass*',
                ],
            ]);
        });

        parent::setUp();
    }

    /** @test */
    function does_not_export_test_classes_ignored_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }
}
