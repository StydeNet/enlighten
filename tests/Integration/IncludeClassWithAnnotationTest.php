<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * @enlighten
 */
class IncludeClassWithAnnotationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // @TODO: make sure the configuration is reset after the test.
        $this->app->config->set([
            'enlighten.tests.ignore' => ['*'],
        ]);
    }

    /** @test */
    function export_test_classes_with_the_enlighten_annotation_even_if_its_ignored_in_the_config()
    {
        $this->assertExampleIsCreated();
    }
}
