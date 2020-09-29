<?php

namespace Tests\TestSuites\Unit;

use Tests\TestCase;

class IncludeMethodWithAnnotationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // @TODO: make sure the configuration is reset after the test.
        $this->app->config->set([
            'enlighten.tests.ignore' => ['*']
        ]);
    }

    /**
     * @test
     * @enlighten
     */
    function export_test_method_with_the_enlighten_annotation_even_if_its_ignored_in_the_config()
    {
        $this->assertExampleIsCreated();
    }

    /**
     * @test
     */
    function it_does_not_export_test_method_ignored_with_the_config()
    {
        $this->assertExampleIsNotCreated();
    }
}
