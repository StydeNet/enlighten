<?php

namespace Tests\Integration;

use Illuminate\Foundation\Testing\RefreshDatabase;

class IncludeMethodWithAnnotationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $this->setConfig([
                'enlighten.tests.ignore' => ['*']
            ]);
        });

        parent::setUp();
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
