<?php

namespace Tests\Suites\Unit;

use Tests\TestCase;

class ExcludeClassWithConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // @TODO: make sure the configuration is reset after the test.
        $this->app->config->set([
            'enlighten.exclude' => [
                '*ExcludeClassWithConfiguration*',
            ],
        ]);
    }

    /** @test */
    function does_not_export_test_classes_with_the_enlighten_exclude_annotation()
    {
        $this->assertExampleIsNotCreated();
    }
}
