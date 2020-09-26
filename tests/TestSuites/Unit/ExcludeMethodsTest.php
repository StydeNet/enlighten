<?php

namespace Tests\Suites\Unit;

use Tests\TestCase;

class ExcludeMethodsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // @TODO: make sure the configuration is reset after the test.
        $this->app->config->set([
            'enlighten.tests.exclude' => [
                'does_not_export_test_methods_excluded_in_the_configuration',
                '*use_wildcards_to_ignore*',
            ],
        ]);
    }

    /** @test */
    function does_not_export_test_methods_excluded_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }

    /** @test */
    function can_use_wildcards_to_ignore_a_test_method_in_the_configuration()
    {
        $this->assertExampleIsNotCreated();
    }

    /**
     * @test
     * @enlighten {"exclude": true}
     */
    function does_not_export_test_methods_with_the_enlighten_exclude_annotation()
    {
        $this->assertExampleIsNotCreated();
    }
}
